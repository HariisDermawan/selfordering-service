<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\AddOrderItemRequest;
use App\Http\Requests\ApplyPromoRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;
    protected $paymentService;

    public function __construct(OrderService $orderService, PaymentService $paymentService)
    {
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
        $this->authorizeResource(Order::class, 'order');
    }

    public function index(Request $request)
    {
        $orders = $this->orderService->getAll($request->all());
        
        return OrderResource::collection($orders);
    }

    public function store(OrderRequest $request)
    {
        $order = $this->orderService->create($request->validated());
        
        return new OrderResource($order);
    }

    public function show(Order $order)
    {
        return new OrderResource($order->load(['items.product', 'payments.paymentMethod', 'customer', 'table']));
    }

    public function addItem(AddOrderItemRequest $request, Order $order)
    {
        $orderItem = $this->orderService->addItem($order->id, $request->validated());
        
        return $this->successResponse($orderItem, 'Item added successfully');
    }

    public function removeItem(Order $order, $itemId)
    {
        $this->orderService->removeItem($order->id, $itemId);
        
        return $this->successResponse(null, 'Item removed successfully');
    }

    public function updateItemQuantity(Request $request, Order $order, $itemId)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:999']);
        
        $orderItem = $this->orderService->updateItemQuantity($order->id, $itemId, $request->quantity);
        
        return $this->successResponse($orderItem, 'Quantity updated');
    }

    public function applyPromo(ApplyPromoRequest $request, Order $order)
    {
        $result = $this->orderService->applyPromotion($order->id, $request->promo_code);
        
        return $this->successResponse($result, 'Promo applied successfully');
    }

    public function cancel(Order $order)
    {
        $order = $this->orderService->cancel($order->id);
        
        return $this->successResponse($order, 'Order cancelled');
    }

    public function calculateTotals(Order $order)
    {
        $totals = $this->orderService->calculateTotals($order);
        
        return $this->successResponse($totals, 'Totals calculated');
    }
}