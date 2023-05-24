<?php

namespace PERSON\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use PERSON\Repository\v1\PersonRepository;
use PERSON\Repository\v1\PersonRepositoryInterface;
use PERSON\Service\v1\PersonService;
use PERSON\Service\v1\PersonServiceInterface;

class PersonServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->router();
    }

    public function register()
    {
        $this->app->bind(
            PersonRepositoryInterface::class,
            PersonRepository::class
        );

        $this->app->bind(
            PersonServiceInterface::class,
            PersonService::class
        );
    }

    public function router(): void
    {
        Route::namespace('\PERSON\Controller\Api')
            ->group(__DIR__ . '/../routes/api.php');
    }

}
