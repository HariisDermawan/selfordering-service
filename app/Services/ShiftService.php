<?php

namespace App\Services;

use App\Models\Shift;
use App\Models\CashDrawerLog;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShiftService
{
    protected $shift;

    public function __construct(Shift $shift)
    {
        $this->shift = $shift;
    }

    public function openShift(array $data): Shift
    {
        return DB::transaction(function () use ($data) {
            $activeShift = $this->shift->where('user_id', auth()->id())
                ->where('status', 'open')
                ->first();

            if ($activeShift) {
                throw new \Exception('You already have an open shift');
            }

            $shift = $this->shift->create([
                'uuid' => (string) Str::uuid(),
                'user_id' => auth()->id(),
                'opened_at' => now(),
                'opening_balance' => $data['opening_balance'],
                'status' => 'open',
                'notes' => $data['notes'] ?? null
            ]);

            CashDrawerLog::create([
                'shift_id' => $shift->id,
                'type' => 'cash_in',
                'amount' => $data['opening_balance'],
                'reason' => 'Opening balance',
                'created_by' => auth()->id()
            ]);

            return $shift;
        });
    }

    public function closeShift(int $shiftId, array $data): Shift
    {
        return DB::transaction(function () use ($shiftId, $data) {
            $shift = $this->findById($shiftId);

            if ($shift->status === 'closed') {
                throw new \Exception('Shift already closed');
            }

            $orders = Order::where('shift_id', $shiftId)
                ->where('status', 'completed')
                ->get();

            $totalSales = $orders->sum('total');
            $cashSales = $orders->filter(function ($order) {
                return $order->payments->contains('paymentMethod.code', 'CASH');
            })->sum('total');
            $nonCashSales = $totalSales - $cashSales;

            $shift->update([
                'closed_at' => now(),
                'closing_balance' => $data['closing_balance'],
                'cash_sales' => $cashSales,
                'non_cash_sales' => $nonCashSales,
                'total_sales' => $totalSales,
                'status' => 'closed',
                'notes' => $data['notes'] ?? $shift->notes
            ]);

            return $shift;
        });
    }

    public function getCurrentShift()
    {
        return $this->shift->where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();
    }

    public function cashMovement(int $shiftId, array $data)
    {
        return DB::transaction(function () use ($shiftId, $data) {
            $shift = $this->findById($shiftId);

            if ($shift->status !== 'open') {
                throw new \Exception('Shift must be open');
            }

            return CashDrawerLog::create([
                'shift_id' => $shiftId,
                'type' => $data['type'],
                'amount' => $data['amount'],
                'reason' => $data['reason'],
                'created_by' => auth()->id()
            ]);
        });
    }

    public function getXReport(int $shiftId): array
    {
        $shift = $this->findById($shiftId);
        $orders = Order::where('shift_id', $shiftId)
            ->where('status', 'completed')
            ->get();

        return [
            'shift' => $shift,
            'total_orders' => $orders->count(),
            'total_sales' => $orders->sum('total'),
            'cash_sales' => $shift->cash_sales,
            'non_cash_sales' => $shift->non_cash_sales,
            'average_order' => $orders->avg('total') ?? 0,
        ];
    }

    public function findById(int $id)
    {
        return $this->shift->with(['user', 'cashDrawerLogs'])->findOrFail($id);
    }

    public function getAll(array $filters = [])
    {
        $query = $this->shift->with('user');

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('opened_at', 'desc')->paginate($filters['per_page'] ?? 15);
    }
}
