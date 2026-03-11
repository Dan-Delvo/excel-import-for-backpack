<?php

namespace DanDelvo\ExcelImportForBackpack\Tests\Fixtures\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use DanDelvo\ExcelImportForBackpack\Http\Controllers\Operations\ImportOperations;
use DanDelvo\ExcelImportForBackpack\Tests\Fixtures\Models\Product;

class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use ImportOperations;

    public function setup(): void
    {
        CRUD::setModel(Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/product');
        CRUD::setEntityNameStrings('product', 'products');
    }
}