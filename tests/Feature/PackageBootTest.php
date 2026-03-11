<?php

namespace DanDelvo\ExcelImportForBackpack\Tests\Feature;

use Backpack\CRUD\ViewNamespaces;
use DanDelvo\ExcelImportForBackpack\Tests\PackageTestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class PackageBootTest extends PackageTestCase
{
    public function test_it_loads_package_views(): void
    {
        $this->assertTrue(View::exists('excel-import::buttons.import'));
        $this->assertTrue(View::exists('excel-import::operations.import'));
    }

    public function test_it_registers_button_view_namespace(): void
    {
        $this->assertContains('excel-import::buttons', ViewNamespaces::getFor('buttons'));
    }

    public function test_it_registers_unique_import_routes(): void
    {
        $getRoute = Route::getRoutes()->getByName('product.import');
        $postRoute = Route::getRoutes()->getByName('product.import.upload');

        $this->assertNotNull($getRoute);
        $this->assertNotNull($postRoute);
        $this->assertSame('admin/product/import', $getRoute->uri());
        $this->assertSame('admin/product/import', $postRoute->uri());
        $this->assertSame(['GET', 'HEAD'], $getRoute->methods());
        $this->assertSame(['POST'], $postRoute->methods());
    }
}