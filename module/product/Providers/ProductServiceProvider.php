<?php

namespace PRODUCT\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use PRODUCT\Controller\Api\v1\ProductController;
use PRODUCT\Repository\v1\ProductRepository;
use PRODUCT\Repository\v1\ProductRepositoryInterface;
use PRODUCT\Service\v1\ProductService;
use PRODUCT\Service\v1\ProductServiceInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(base_path('\module\product\database\migrations'));
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'PRODUCT\\database\\factories\\' . class_basename($modelName) . 'Factory';
        });
        $this->router();
    }

    public function register()
    {
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );


        $this->app
            ->when(ProductController::class)
            ->needs(ProductServiceInterface::class)
            ->give(function ($app) {
                return $app->make(ProductService::class, [$app->make(ProductRepositoryInterface::class)]);
            });
    }

    public function router(): void
    {
        Route::namespace('\PRODUCT\Controller\Api')
            ->group(__DIR__ . '/../routes/api.php');

    }
}
