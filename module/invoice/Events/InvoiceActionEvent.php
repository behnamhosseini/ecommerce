<?php

namespace INVOICE\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvoiceActionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inventory;
    public $productId;


    public function __construct($inventory, $productId)
    {
        $this->inventory = $inventory;
        $this->productId = $productId;
    }

}
