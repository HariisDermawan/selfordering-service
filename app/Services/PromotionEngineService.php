<?php

namespace App\Services;

use App\Models\Promotion;
use App\Models\Order;
use App\Models\BuyXGetYRule;

class PromotionEngineService
{
    public function calculateDiscount(Promotion $promotion, Order $order): float
    {
        $subtotal = $order->items->sum('total');
        
        if ($subtotal < $promotion->min_purchase) {
            return 0;
        }

        return match($promotion->type) {
            'percentage' => $this->calculatePercentageDiscount($promotion, $subtotal),
            'fixed' => $this->calculateFixedDiscount($promotion, $subtotal),
            'buy_x_get_y' => $this->calculateBuyXGetYDiscount($promotion, $order),
            'bundle' => $this->calculateBundleDiscount($promotion, $order),
            default => 0,
        };
    }

    protected function calculatePercentageDiscount(Promotion $promotion, float $subtotal): float
    {
        $discount = $subtotal * ($promotion->discount_value / 100);
        
        if ($promotion->max_discount && $discount > $promotion->max_discount) {
            $discount = $promotion->max_discount;
        }
        
        return round($discount, 2);
    }

    protected function calculateFixedDiscount(Promotion $promotion, float $subtotal): float
    {
        return min($promotion->discount_value, $subtotal);
    }

    protected function calculateBuyXGetYDiscount(Promotion $promotion, Order $order): float
    {
        $rule = BuyXGetYRule::where('promotion_id', $promotion->id)->first();
        
        if (!$rule) {
            return 0;
        }

        $buyProductItems = $order->items->filter(function ($item) use ($rule) {
            return $item->product_id === $rule->buy_product_id;
        });

        $totalBuyQuantity = $buyProductItems->sum('quantity');
        
        if ($totalBuyQuantity < $rule->buy_quantity) {
            return 0;
        }

        $freeSets = floor($totalBuyQuantity / $rule->buy_quantity);
        $freeQuantity = $freeSets * $rule->get_quantity;
        
        $getProduct = $rule->getProduct;
        $discount = $getProduct->price * $freeQuantity;

        return round($discount, 2);
    }

    protected function calculateBundleDiscount(Promotion $promotion, Order $order): float
    {
        $bundleItems = $promotion->products;
        $hasAllItems = true;
        
        foreach ($bundleItems as $bundleItem) {
            $found = $order->items->contains('product_id', $bundleItem->id);
            if (!$found) {
                $hasAllItems = false;
                break;
            }
        }

        if (!$hasAllItems) {
            return 0;
        }

        $bundleTotal = $bundleItems->sum('price');
        $discount = $bundleTotal - $promotion->discount_value;
        
        return max(0, round($discount, 2));
    }

    public function getValidPromotions(Order $order)
    {
        $now = now();
        
        return Promotion::where('is_active', true)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->get()
            ->filter(function ($promotion) use ($order) {
                $subtotal = $order->items->sum('total');
                return $subtotal >= $promotion->min_purchase;
            });
    }
}