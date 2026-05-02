<?php

namespace App\Services;

use App\Models\KitchenTicket;
use App\Models\KitchenTicketItem;
use Illuminate\Support\Facades\DB;

class KitchenService
{
    protected $kitchenTicket;

    public function __construct(KitchenTicket $kitchenTicket)
    {
        $this->kitchenTicket = $kitchenTicket;
    }

    public function getPendingTickets($status = null)
    {
        $query = $this->kitchenTicket->with(['order', 'items.orderItem.product']);

        if ($status) {
            $query->where('status', $status);
        } else {
            $query->whereIn('status', ['pending', 'cooking', 'ready']);
        }

        return $query->orderBy('pending_at')->get();
    }

    public function updateStatus(int $ticketId, string $status)
    {
        return DB::transaction(function () use ($ticketId, $status) {
            $ticket = $this->kitchenTicket->findOrFail($ticketId);

            $ticket->update([
                'status' => $status,
                "{$status}_at" => now()
            ]);

            return $ticket;
        });
    }

    public function updateItemStatus(int $ticketItemId, string $status)
    {
        $item = KitchenTicketItem::findOrFail($ticketItemId);
        $item->update(['status' => $status]);
        return $item;
    }

    public function getTicketByOrder(int $orderId)
    {
        return $this->kitchenTicket->where('order_id', $orderId)->first();
    }

    public function findById(int $id)
    {
        return $this->kitchenTicket->with(['order', 'items.orderItem.product'])->findOrFail($id);
    }
}
