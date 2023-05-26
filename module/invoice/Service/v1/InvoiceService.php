<?php

namespace INVOICE\Service\v1;

use INVOICE\Models\Invoice;
use INVOICE\Models\InvoiceItem;
use INVOICE\Repository\v1\InvoiceRepositoryInterface;
use Illuminate\Support\Facades\DB;
use PRODUCT\Service\v1\ProductServiceInterface;

class InvoiceService implements InvoiceServiceInterface
{
    private $invoiceRepository;
    private $productService;

    public function __construct(InvoiceRepositoryInterface $invoiceRepository, ProductServiceInterface $productService)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->productService = $productService;
    }

    public function getAllInvoices()
    {
        return $this->invoiceRepository->getAll();
    }

    public function getInvoice(int $id): ?Invoice
    {
        return $this->invoiceRepository->getById($id);
    }

    public function createInvoice(array $data): ?Invoice
    {
        return DB::transaction(function () use ($data) {
            $invoice = $this->invoiceRepository->create($data);

            $invoiceItems = [];
            foreach ($data['invoice_items'] as $itemId) {
                $product = $this->productService->getProductById($itemId);
                if ($product) {
                    $invoiceItems[] = new InvoiceItem([
                        'product_id' => $product->id,
                        'quantity' => $product->quantity,
                        'price' => $product->price,
                       'amount' => $this->calculateAmount($product->quantity, $product->price),
                        'discount' => $this->calculateDiscount($this->calculateAmount($product->quantity, $product->price), $product->discount),
                        'total_after_discount' => $this->calculateTotalAfterDiscount($this->calculateAmount($product->quantity, $product->price), $product->discount),
                        'tax' => $this->calculateTax($this->calculateTotalAfterDiscount($this->calculateAmount($product->quantity, $product->price), $product->discount), $product->tax),
                        'total_due' => $this->calculateTotalDue($this->calculateTotalAfterDiscount($this->calculateAmount($product->quantity, $product->price), $product->discount), $product->tax),
                    ]);
                }
            }

            $invoice->items()->saveMany($invoiceItems);

            return $invoice;
        });
    }

    public function updateInvoice(int $id, array $data): ?Invoice
    {
        //ToDo
    }

    public function deleteInvoice(int $id): bool
    {
        //ToDo
    }

    public function calculateAmount(float $quantity, int $price): int
    {
        return (int)($quantity * $price);
    }

    public function calculateDiscount(float $amount, int $discount): int
    {
        return (int)($amount * $discount / 100);
    }

    public function calculateTotalAfterDiscount(int $amount, int $discount): int
    {
        return $amount - $discount;
    }

    public function calculateTax(int $totalAfterDiscount, int $tax): int
    {
        return $totalAfterDiscount * $tax / 100;
    }

    public function calculateTotalDue(int $totalAfterDiscount, int $tax): int
    {
        return $totalAfterDiscount + $tax;
    }

    public function calculateTotalSum(array $amounts): int
    {
        return array_sum($amounts);
    }
}
