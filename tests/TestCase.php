<?php

declare(strict_types=1);

namespace Tests;

use Appleton\Faq\ServiceProvider;
use Appleton\SpatieLaravelPermissionMock\Models\PermissionUuid;
use Appleton\SpatieLaravelPermissionMock\Models\UserUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Schema;
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

        Schema::create('faqables', function ($table) {
            $table->uuid('id')->primary();
            $table->timestamps();
        });
    }

    protected function getNewUser(?string $permission = null): Model
    {
        $user = $this->getUserModel();
        $user->fill([
            'name' => is_null($permission) ? 'Test User' : 'Test Admin User',
            'email' => is_null($permission) ? 'test.user@home.com' : 'test.admin.user@home.com',
            'password' => bcrypt('password'),
        ]);
        $user->save();

        if (!is_null($permission)) {
            $permission = PermissionUuid::create(['name' => $permission]);
            $user->givePermissionTo($permission);
        }

        return $user;
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

    protected function getFaqable(): Model
    {
        $faqable = new class extends Model {
            use HasUuids;

            protected $table = 'faqables';
            protected $guarded = [];
        };

        $faqable->save();

        return $faqable;
    }

    protected function getUserModel(): Model
    {
        return new class extends UserUuid {
            protected $table = 'users';
            protected $guarded = [];
        };
    }
}
