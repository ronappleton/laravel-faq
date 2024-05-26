<?php

declare(strict_types=1);

namespace Tests;

use Appleton\Faq\ServiceProvider;
use Appleton\SpatieLaravelPermissionMock\Models\UserUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('mock-permissions.uuids', true);

        $this->artisan('migrate:fresh', ['--database' => 'sqlite']);

        config()->set([
            'auth.guards.api.driver' => 'session',
            'auth.guards.api.provider' => 'users',
            'auth.providers.users.driver' => 'eloquent',
            'auth.providers.users.model' => UserUuid::class,
        ]);
    }

    protected function getNewUser(): Model
    {
        return UserUuid::factory()->create();
    }

    /**
     * @return array<int, class-string>)
     */
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
            \Appleton\TypedConfig\ServiceProvider::class,
            PermissionServiceProvider::class,
            \Appleton\SpatieLaravelPermissionMock\ServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
