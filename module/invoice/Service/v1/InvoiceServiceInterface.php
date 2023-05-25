<?php

namespace INVOICE\Service\v1;

use INVOICE\Models\Invoice;

interface InvoiceServiceInterface
{
    public function getInvoice(int $id): ?Invoice;

    public function getAllInvoices();

    public function createInvoice(array $data): ?Invoice;

    public function updateInvoice(int $id, array $data): ?Invoice;

    public function deleteInvoice(int $id): bool;
}
