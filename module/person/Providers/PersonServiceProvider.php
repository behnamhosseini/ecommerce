<?php

namespace PERSON\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use PERSON\Controller\Api\v1\PersonController;
use PERSON\Repository\v1\PersonRepository;
use PERSON\Repository\v1\PersonRepositoryInterface;
use PERSON\Service\v1\PersonService;
use PERSON\Service\v1\PersonServiceInterface;

class PersonServiceProvider extends ServiceProvider
{
    public function boot()
    {
//        $this->loadFactoriesFrom(base_path('\modules\person\database\factories'));
        $this->loadMigrationsFrom(base_path('\module\person\database\migrations'));
        $this->router();
    }

    public function register()
    {
        $this->app->bind(
            PersonRepositoryInterface::class,
            PersonRepository::class
        );

        $this->app
            ->when(PersonController::class)
            ->needs(PersonServiceInterface::class)
            ->give(function ($app) {
                return $app->make(PersonService::class, [$app->make(PersonRepositoryInterface::class)]);
            });
    }

    public function router(): void
    {
        Route::namespace('\PERSON\Controller\Api')
            ->group(__DIR__ . '/../routes/api.php');

    }
}
