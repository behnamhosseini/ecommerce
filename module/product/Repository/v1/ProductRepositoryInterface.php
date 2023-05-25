<?php


namespace PRODUCT\Repository\v1;


interface  ProductRepositoryInterface
{
    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function findById(int $id);

    public function getAll();
}
