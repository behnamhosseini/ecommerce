<?php

namespace PRODUCT\Controller\Api\v1;

use App\Http\Controllers\Controller;
use PRODUCT\Requests\CreateProductRequest;
use PRODUCT\Requests\UpdateProductRequest;
use PRODUCT\Resources\ProductResource;
use PRODUCT\Service\v1\ProductServiceInterface;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->getAllProducts();
        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $product = $this->productService->getProductById($id);
        return new ProductResource($product);
    }

    public function store(CreateProductRequest $request)
    {
        $productData = $request->validated();
        $product = $this->productService->createProduct($productData);
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $productData = $request->validated();
        $product = $this->productService->updateProduct($id, $productData);
        return new ProductResource($product);
    }

    public function destroy($id)
    {
        $this->productService->deleteProduct($id);
        return response()->noContent();
    }
}
