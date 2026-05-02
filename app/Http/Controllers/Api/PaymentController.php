<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\SplitPaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function processPayment(PaymentRequest $request, Order $order)
    {
        $result = $this->paymentService->processPayment($order->id, $request->validated());
        
        return $this->successResponse($result, 'Payment processed successfully');
    }

    public function processSplitPayment(SplitPaymentRequest $request, Order $order)
    {
        $result = $this->paymentService->processSplitPayment($order->id, $request->validated());
        
        return $this->successResponse($result, 'Split payment processed successfully');
    }

    public function getOrderPayments(Order $order)
    {
        $payments = $this->paymentService->getAll(['order_id' => $order->id]);
        
        return PaymentResource::collection($payments);
    }

    public function refund(Request $request, $paymentId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string'
        ]);

        $refund = $this->paymentService->processRefund($paymentId, $request->all());
        
        return $this->successResponse($refund, 'Refund requested successfully');
    }
}