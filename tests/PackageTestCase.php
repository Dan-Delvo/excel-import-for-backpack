<?php

namespace DanDelvo\ExcelImportForBackpack\Tests;

use Backpack\Basset\BassetServiceProvider;
use Backpack\CRUD\BackpackServiceProvider;
use DanDelvo\ExcelImportForBackpack\AddonServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\ExcelServiceProvider;
use Orchestra\Testbench\TestCase;
use Prologue\Alerts\AlertsServiceProvider;

abstract class PackageTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('products', function ($table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('sku')->nullable();
            $table->timestamps();
        });

        Route::group([
            'middleware' => array_merge(
                (array) config('backpack.base.web_middleware', 'web'),
                (array) config('backpack.base.middleware_key', 'admin')
            ),
            'prefix' => config('backpack.base.route_prefix', 'admin'),
        ], function () {
            Route::crud('product', \DanDelvo\ExcelImportForBackpack\Tests\Fixtures\Http\Controllers\ProductCrudController::class);
        });
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=');
        $app['config']->set('backpack.base.route_prefix', 'admin');
        $app['config']->set('backpack.base.web_middleware', ['web']);
        $app['config']->set('backpack.base.middleware_key', []);
        $app['config']->set('backpack.ui.view_namespace', 'crud::');
        $app['config']->set('backpack.ui.view_namespace_fallback', 'crud::');
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            AlertsServiceProvider::class,
            BassetServiceProvider::class,
            BackpackServiceProvider::class,
            ExcelServiceProvider::class,
            AddonServiceProvider::class,
        ];
    }
}