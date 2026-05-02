<?php

namespace App\Services;

use App\Models\DeliveryOrder;
use App\Models\DeliveryStatusLog;
use Illuminate\Support\Facades\DB;

class DeliveryService
{
    protected $deliveryOrder;

    public function __construct(DeliveryOrder $deliveryOrder)
    {
        $this->deliveryOrder = $deliveryOrder;
    }

    public function createDelivery(array $data): DeliveryOrder
    {
        return DB::transaction(function () use ($data) {
            $delivery = $this->deliveryOrder->create($data);

            $this->logStatus($delivery->id, 'pending', 'Delivery order created');

            return $delivery;
        });
    }

    public function updateStatus(int $deliveryId, string $status, string $notes = null): DeliveryOrder
    {
        return DB::transaction(function () use ($deliveryId, $status, $notes) {
            $delivery = $this->findById($deliveryId);
            $delivery->update(['status' => $status]);

            if ($status === 'delivered') {
                $delivery->update(['delivered_at' => now()]);
            }

            $this->logStatus($deliveryId, $status, $notes);

            return $delivery;
        });
    }

    protected function logStatus(int $deliveryId, string $status, string $notes = null)
    {
        DeliveryStatusLog::create([
            'delivery_order_id' => $deliveryId,
            'status' => $status,
            'notes' => $notes
        ]);
    }

    public function assignDriver(int $deliveryId, int $driverId): DeliveryOrder
    {
        $delivery = $this->findById($deliveryId);
        $delivery->update(['driver_id' => $driverId]);

        $this->logStatus($deliveryId, $delivery->status, "Assigned to driver ID: {$driverId}");

        return $delivery;
    }

    public function findById(int $id)
    {
        return $this->deliveryOrder->with(['order', 'driver', 'platform', 'statusLogs'])
            ->findOrFail($id);
    }

    public function getByOrder(int $orderId)
    {
        return $this->deliveryOrder->where('order_id', $orderId)->first();
    }

    public function getAll(array $filters = [])
    {
        $query = $this->deliveryOrder->with(['order', 'driver']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['driver_id'])) {
            $query->where('driver_id', $filters['driver_id']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($filters['per_page'] ?? 15);
    }
}
