<?php

namespace PERSON\Providers;

use Illuminate\Support\ServiceProvider;

class PersonServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->router();
    }

    public function router(): void
    {
        $this->app->router->group([
            'namespace' => '\PERSON\Controller\Api',
        ], function ($router) {
            require __DIR__ . '/../routes/api.php';
        });
    }

}
