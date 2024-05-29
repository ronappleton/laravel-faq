<?php

declare(strict_types=1);

namespace Appleton\Faq;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->register(Providers\RouteServiceProvider::class);

        $this->publishes([
            __DIR__ . '/../config/faq.php' => config_path('faq.php'),
        ]);

        $this->mergeConfigFrom(__DIR__ . '/../config/faq.php', 'faq');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'faq-migrations');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'faq');
        $this->extendValidation();
    }

    private function extendValidation(): void
    {
        Validator::extend('morph_exists', function ($attribute, $value, $parameters, $validator) {
            if (! $type = Arr::get($validator->getData(), $parameters[0], false)) {
                return false;
            }

            $type = Relation::getMorphedModel($type) ?? $type;

            if (!class_exists($type)) {
                return false;
            }

            return resolve($type)->where('id', $value)->exists();
        });
    }
}
