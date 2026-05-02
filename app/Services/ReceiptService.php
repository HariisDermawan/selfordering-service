<?php

namespace App\Services;

use App\Models\Receipt;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReceiptService
{
    protected $receipt;

    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
    }

    public function generateReceipt(Order $order): Receipt
    {
        return DB::transaction(function () use ($order) {
            $receiptNumber = 'RCT-' . date('Ymd') . '-' . $order->id;
            
            $content = $this->formatReceiptContent($order);
            
            return $this->receipt->create([
                'uuid' => (string) Str::uuid(),
                'order_id' => $order->id,
                'receipt_number' => $receiptNumber,
                'receipt_content' => $content,
                'print_count' => 0
            ]);
        });
    }

    public function reprint(int $receiptId): Receipt
    {
        $receipt = $this->findById($receiptId);
        $receipt->increment('print_count');
        $receipt->update(['printed_at' => now()]);
        return $receipt;
    }

    protected function formatReceiptContent(Order $order): string
    {
        $content = "========================\n";
        $content .= "    RESTAURANT POS\n";
        $content .= "========================\n";
        $content .= "Order #: {$order->order_number}\n";
        $content .= "Date: {$order->ordered_at->format('Y-m-d H:i:s')}\n";
        $content .= "Cashier: {$order->user->name}\n";
        $content .= "========================\n\n";
        
        foreach ($order->items as $item) {
            $content .= sprintf(
                "%-20s x%d = %s\n",
                substr($item->product->name, 0, 20),
                $item->quantity,
                number_format($item->total, 2)
            );
            
            foreach ($item->modifiers as $modifier) {
                $content .= sprintf("  + %s\n", $modifier->modifierOption->name);
            }
        }
        
        $content .= "\n========================\n";
        $content .= sprintf("Subtotal: %'.12s\n", number_format($order->subtotal, 2));
        
        if ($order->discount_amount > 0) {
            $content .= sprintf("Discount: %'.12s\n", number_format($order->discount_amount, 2));
        }
        
        $content .= sprintf("Tax (PB1): %'.12s\n", number_format($order->tax_amount, 2));
        $content .= sprintf("Service Charge: %'.12s\n", number_format($order->service_charge, 2));
        $content .= "------------------------\n";
        $content .= sprintf("TOTAL: %'.13s\n", number_format($order->total, 2));
        
        if ($order->paid_amount > 0) {
            $content .= sprintf("Paid: %'.13s\n", number_format($order->paid_amount, 2));
            if ($order->change_amount > 0) {
                $content .= sprintf("Change: %'.11s\n", number_format($order->change_amount, 2));
            }
        }
        
        $content .= "========================\n";
        $content .= "   Thank you!\n";
        $content .= "========================\n";
        
        return $content;
    }

    public function findById(int $id)
    {
        return $this->receipt->with('order')->findOrFail($id);
    }

    public function getByOrder(int $orderId)
    {
        return $this->receipt->where('order_id', $orderId)->first();
    }
}