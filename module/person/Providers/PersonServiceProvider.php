<?php

namespace PERSON\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PersonServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->router();
    }

    public function router(): void
    {
        Route::namespace('\PERSON\Controller\Api')
            ->group(__DIR__ . '/../routes/api.php');
    }

}
