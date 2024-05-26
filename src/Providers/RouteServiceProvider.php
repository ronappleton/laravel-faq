<?php

declare(strict_types=1);

namespace Appleton\Faq\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    /**
     * @var string
     */
    protected $namespace = 'Appleton\Faq\Http\Controllers';

    public function boot(): void
    {
        parent::boot();

        $this->routes(function () {
            Route::prefix(config()->string('faq.route_prefix', 'api'))
                ->namespace($this->namespace)
                ->group(__DIR__.'/../../routes/api.php');
        });
    }
}
