<?php

namespace INVOICE\Providers;

use Illuminate\Database\Eloquent\Factories\Factory as EloquentFactory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use INVOICE\Controller\Api\v1\InvoiceController;
use INVOICE\Repository\v1\InvoiceRepository;
use INVOICE\Repository\v1\InvoiceRepositoryInterface;
use INVOICE\Service\v1\InvoiceService;
use INVOICE\Service\v1\InvoiceServiceInterface;
use Illuminate\Database\Eloquent\Factories\Factory;
use PERSON\Repository\v1\PersonRepositoryInterface;
use PERSON\Service\v1\PersonService;
use PERSON\Service\v1\PersonServiceInterface;
use PRODUCT\Repository\v1\ProductRepositoryInterface;
use PRODUCT\Service\v1\ProductService;
use PRODUCT\Service\v1\ProductServiceInterface;

class InvoiceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(base_path('\module\invoice\database\migrations'));
        $this->router();
    }

    public function register()
    {
        $this->app->extend(EloquentFactory::class, function ($factory) {
            $factory->useNamespace('INVOICE\\database\\factories');

            return $factory;
        });

        $this->app->bind(
            InvoiceRepositoryInterface::class,
            InvoiceRepository::class
        );

        $this->app
            ->when(InvoiceService::class)
            ->needs(ProductServiceInterface::class)
            ->give(function ($app) {
                return $app->make(ProductService::class, [$app->make(ProductRepositoryInterface::class)]);
            });
        $this->app
            ->when(InvoiceService::class)
            ->needs(PersonServiceInterface::class)
            ->give(function ($app) {
                return $app->make(PersonService::class, [$app->make(PersonRepositoryInterface::class)]);
            });

        $this->app
            ->when(InvoiceController::class)
            ->needs(InvoiceServiceInterface::class)
            ->give(function ($app) {
                return $app->make(InvoiceService::class, [$app->make(InvoiceRepositoryInterface::class),$app->make(ProductRepositoryInterface::class)]);
            });


    }

    public function router(): void
    {
        Route::namespace('\INVOICE\Controller\Api')
            ->group(__DIR__ . '/../routes/api.php');

    }
}
