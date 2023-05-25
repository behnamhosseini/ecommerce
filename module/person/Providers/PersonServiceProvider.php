<?php

namespace PERSON\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use PERSON\Controller\Api\v1\PersonController;
use PERSON\Repository\v1\PersonRepository;
use PERSON\Repository\v1\PersonRepositoryInterface;
use PERSON\Service\v1\PersonService;
use PERSON\Service\v1\PersonServiceInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(base_path('\module\person\database\migrations'));
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'PERSON\\database\\factories\\' . class_basename($modelName) . 'Factory';
        });
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
