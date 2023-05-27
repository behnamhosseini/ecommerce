<?php


namespace INVOICE\Repository\v1;


use INVOICE\Models\Invoice;

interface  InvoiceRepositoryInterface
{
    public function create(array $data): Invoice;

    public function update(int $id, array $data);

    public function delete(int $id);

    public function findById(int $id);

    public function getAll();

    public function attachItems(Invoice $invoice,$data);
}
