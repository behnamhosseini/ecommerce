<?php

namespace PRODUCT\Service\v1;

interface ProductServiceInterface
{
    public function createProduct(array $data);

    public function updateProduct(int $id, array $data);

    public function deleteProduct(int $id);

    public function getProductById(int $id);

    public function getAllProducts();

}
