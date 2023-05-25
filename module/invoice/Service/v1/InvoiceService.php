<?php

namespace INVOICE\Service\v1;


use INVOICE\Models\Invoice;
use INVOICE\Repository\v1\InvoiceRepositoryInterface;

class InvoiceService implements InvoiceServiceInterface
{

    private $invoiceRepository;

    public function __construct(InvoiceRepositoryInterface $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
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
        // Validation and business logic
        // Calculate amount, total after discount, tax, etc.

        $invoice = $this->invoiceRepository->create($data);

        // Fire the event
//        event(new InvoiceCreated($invoice));

        return $invoice;
    }

    public function updateInvoice(int $id, array $data): ?Invoice
    {
        // Validation and business logic
        // Calculate amount, total after discount, tax, etc.

        $invoice = $this->invoiceRepository->update($id, $data);

        // Fire the event
//        event(new InvoiceUpdated($invoice));

        return $invoice;
    }

    public function deleteInvoice(int $id): bool
    {
        $invoice = $this->invoiceRepository->getById($id);

        $this->invoiceRepository->delete($id);

        // Fire the event
//        event(new InvoiceDeleted($invoice));
    }


    protected function calculateAmount(float $quantity, int $price): int
    {
        return (int) ($quantity * $price);
    }

    protected function calculateDiscount(float $amount, int $discount) :int
    {
        return (int) ($amount * $discount  / 100 );
    }

    protected function calculateTotalAfterDiscount(int $amount, int $discount): int
    {
        return $amount - $discount;
    }

    protected function calculateTax(int $totalAfterDiscount, int $tax): int
    {
        return $totalAfterDiscount * $tax / 100;
    }

    protected function calculateTotalDue(int $totalAfterDiscount, int $tax): int
    {
        return $totalAfterDiscount + $tax;
    }

    protected function calculateTotalSum(array $amounts): int
    {
        return array_sum($amounts);
    }
}
