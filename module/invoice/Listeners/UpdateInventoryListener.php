<?php

namespace INVOICE\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use INVOICE\Events\InvoiceActionEvent;
use PRODUCT\Service\v1\ProductServiceInterface;

class UpdateInventoryListener
{

    private ProductServiceInterface $ProductService;

    public function __construct(ProductServiceInterface $ProductService)
    {
        $this->ProductService = $ProductService;
    }

    public function handle(InvoiceActionEvent $event): void
    {
        $inventory = $event->inventory;
        $this->ProductService->updateProduct($event->productId, ['inventory' => $inventory]);
    }
}
