<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;

class ReportService
{
    public function dailyReport($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::today();
        
        $orders = Order::whereDate('ordered_at', $date)
            ->where('status', 'completed')
            ->get();
        
        return [
            'date' => $date->format('Y-m-d'),
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total'),
            'average_order' => $orders->avg('total') ?? 0,
            'payment_methods' => $this->getPaymentBreakdown($date),
            'top_products' => $this->getTopProducts($date),
        ];
    }

    public function salesReport($startDate, $endDate)
    {
        $orders = Order::whereBetween('ordered_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();
        
        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total'),
            'daily_breakdown' => $this->getDailyBreakdown($startDate, $endDate),
        ];
    }

    protected function getPaymentBreakdown($date)
    {
        return Payment::whereDate('paid_at', $date)
            ->with('paymentMethod')
            ->get()
            ->groupBy('paymentMethod.name')
            ->map(function ($payments) {
                return [
                    'total' => $payments->sum('amount'),
                    'count' => $payments->count()
                ];
            });
    }

    protected function getTopProducts($date, $limit = 10)
    {
        return Order::whereDate('ordered_at', $date)
            ->with('items.product')
            ->get()
            ->flatMap(function ($order) {
                return $order->items;
            })
            ->groupBy('product_id')
            ->map(function ($items) {
                return [
                    'product_name' => $items->first()->product->name,
                    'quantity' => $items->sum('quantity'),
                    'revenue' => $items->sum('total')
                ];
            })
            ->sortByDesc('quantity')
            ->take($limit)
            ->values();
    }

    protected function getDailyBreakdown($startDate, $endDate)
    {
        $dates = collect();
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        while ($current <= $end) {
            $orders = Order::whereDate('ordered_at', $current)
                ->where('status', 'completed')
                ->get();
            
            $dates->push([
                'date' => $current->format('Y-m-d'),
                'orders' => $orders->count(),
                'revenue' => $orders->sum('total')
            ]);
            
            $current->addDay();
        }
        
        return $dates;
    }
}