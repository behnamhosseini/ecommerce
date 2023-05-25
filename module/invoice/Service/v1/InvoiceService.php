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

    public function getInvoice(int $id): ?Invoice
    {
        // TODO: Implement getInvoice() method.
    }

    public function getAllInvoices()
    {
        // TODO: Implement getAllInvoices() method.
    }

    public function createInvoice(array $data): ?Invoice
    {
        // TODO: Implement createInvoice() method.
    }

    public function updateInvoice(int $id, array $data): ?Invoice
    {
        // TODO: Implement updateInvoice() method.
    }

    public function deleteInvoice(int $id): bool
    {
        // TODO: Implement deleteInvoice() method.
    }
}
