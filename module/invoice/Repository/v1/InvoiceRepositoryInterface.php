<?php


namespace INVOICE\Repository\v1;


interface  InvoiceRepositoryInterface
{
    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function findById(int $id);

    public function getAll();
}
