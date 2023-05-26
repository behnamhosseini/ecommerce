<?php

namespace INVOICE\Service\v1;

use INVOICE\Models\Invoice;

interface InvoiceServiceInterface
{
    public function getInvoice(int $id): ?Invoice;

    public function getAllInvoices();

    public function createInvoice(array $data);

    public function updateInvoice(int $id, array $data);

    public function deleteInvoice(int $id): bool;
}
