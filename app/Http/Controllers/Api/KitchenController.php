<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateKitchenStatusRequest;
use App\Http\Resources\KitchenTicketResource;
use App\Models\KitchenTicket;
use App\Services\KitchenService;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    protected $kitchenService;

    public function __construct(KitchenService $kitchenService)
    {
        $this->kitchenService = $kitchenService;
    }

    public function index(Request $request)
    {
        $tickets = $this->kitchenService->getPendingTickets($request->get('status'));
        
        return KitchenTicketResource::collection($tickets);
    }

    public function updateStatus(UpdateKitchenStatusRequest $request, KitchenTicket $ticket)
    {
        $ticket = $this->kitchenService->updateStatus($ticket->id, $request->status);
        
        return new KitchenTicketResource($ticket);
    }

    public function updateItemStatus(Request $request, $ticketItemId)
    {
        $request->validate(['status' => 'required|in:pending,cooking,ready,served']);
        
        $item = $this->kitchenService->updateItemStatus($ticketItemId, $request->status);
        
        return $this->successResponse($item, 'Status updated');
    }

    public function show(KitchenTicket $ticket)
    {
        return new KitchenTicketResource($ticket->load(['order', 'items.orderItem.product']));
    }

    public function getOrdersByStatus($status)
    {
        $tickets = $this->kitchenService->getPendingTickets($status);
        
        return KitchenTicketResource::collection($tickets);
    }
}