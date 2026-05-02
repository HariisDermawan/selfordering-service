<?php

namespace App\Services;

use App\Models\SplitBill;
use App\Models\SplitBillItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SplitBillService
{
    protected $splitBill;

    public function __construct(SplitBill $splitBill)
    {
        $this->splitBill = $splitBill;
    }

    public function splitByItems(int $orderId, array $itemGroups): array
    {
        return DB::transaction(function () use ($orderId, $itemGroups) {
            $splitBills = [];
            
            foreach ($itemGroups as $group) {
                $splitBill = $this->splitBill->create([
                    'order_id' => $orderId,
                    'split_number' => (string) Str::uuid(),
                    'amount' => $group['amount'],
                    'status' => 'pending'
                ]);
                
                foreach ($group['item_ids'] as $itemId) {
                    SplitBillItem::create([
                        'split_bill_id' => $splitBill->id,
                        'order_item_id' => $itemId
                    ]);
                }
                
                $splitBills[] = $splitBill;
            }
            
            return $splitBills;
        });
    }

    public function splitEqual(int $orderId, int $numberOfPeople, float $totalAmount): array
    {
        return DB::transaction(function () use ($orderId, $numberOfPeople, $totalAmount) {
            $amountPerPerson = round($totalAmount / $numberOfPeople, 2);
            $splitBills = [];
            
            for ($i = 1; $i <= $numberOfPeople; $i++) {
                $amount = $i === $numberOfPeople ? 
                    $totalAmount - ($amountPerPerson * ($numberOfPeople - 1)) : 
                    $amountPerPerson;
                
                $splitBill = $this->splitBill->create([
                    'order_id' => $orderId,
                    'split_number' => (string) Str::uuid(),
                    'amount' => $amount,
                    'status' => 'pending'
                ]);
                
                $splitBills[] = $splitBill;
            }
            
            return $splitBills;
        });
    }

    public function markAsPaid(int $splitBillId): SplitBill
    {
        $splitBill = $this->findById($splitBillId);
        $splitBill->update(['status' => 'paid']);
        
        // Check if all split bills are paid
        $allPaid = $this->splitBill->where('order_id', $splitBill->order_id)
            ->where('status', 'pending')
            ->doesntExist();
        
        if ($allPaid) {
            $splitBill->order->update(['payment_status' => 'paid']);
        }
        
        return $splitBill;
    }

    public function findById(int $id)
    {
        return $this->splitBill->with(['items.orderItem', 'order'])->findOrFail($id);
    }

    public function getByOrder(int $orderId)
    {
        return $this->splitBill->where('order_id', $orderId)->get();
    }
}