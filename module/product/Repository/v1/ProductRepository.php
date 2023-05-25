<?php


namespace PRODUCT\Repository\v1;

use PRODUCT\Models\Product;

class ProductRepository  implements ProductRepositoryInterface
{
    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(int $id, array $data)
    {
        $product = $this->findById($id);
        $product->update($data);
        return $product;
    }

    public function delete(int $id)
    {
        $product = $this->findById($id);
        $product->delete();
    }

    public function findById(int $id)
    {
        return Product::findOrFail($id);
    }

    public function getAll()
    {
        return Product::all();
    }
}
