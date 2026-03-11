# ExcelImportForBackpack

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![The Whole Fruit Manifesto](https://img.shields.io/badge/writing%20standard-the%20whole%20fruit-brightgreen)](https://github.com/the-whole-fruit/manifesto)

ExcelImportForBackpack adds an `import` operation to [Backpack for Laravel](https://backpackforlaravel.com/) CRUD controllers.

It provides:

- an Import button on the List operation;
- an import screen that reads Excel or CSV headers in the browser;
- automatic header-to-database-column mapping;
- server-side import handling using `maatwebsite/excel`.


## Screenshots

Screenshots can be added later. The package is fully usable without published assets.


## Installation

Prerequisites:

- PHP ZIP extension enabled (`ext-zip`)
- A Backpack v7 compatible Laravel application

``` bash
composer require dan-delvo/excel-import-for-backpack
```

The package uses Laravel auto-discovery, so no manual service provider registration is required.

If Composer reports that `ext-zip` is missing, enable the ZIP extension in your CLI PHP installation and run the command again.

If you want to customize the package views or config, publish them with:

```bash
php artisan vendor:publish --tag=excel-import-for-backpack
```

## Usage

Add the import operation trait to your CRUD controller:

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use DanDelvo\ExcelImportForBackpack\Http\Controllers\Operations\ImportOperations;

class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use ImportOperations;

    public function setup()
    {
        CRUD::setModel(Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/product');
        CRUD::setEntityNameStrings('product', 'products');
    }
}
```

Once the trait is added, the package will:

- register `GET /admin/{entity}/import` and `POST /admin/{entity}/import` routes;
- add an Import button to the List operation;
- render the import screen from the package views.

The import expects the first row of the file to contain headings.

## Queue Behavior

The import class implements `ShouldQueue` and `WithChunkReading`.

- If your application queue driver is `sync`, the import runs immediately.
- If your application uses a real queue connection, make sure a queue worker is running.


## Overwriting

Publish the package resources:

```bash
php artisan vendor:publish --tag=excel-import-for-backpack-views
php artisan vendor:publish --tag=excel-import-for-backpack-config
```

Published views will be placed in:

```text
resources/views/vendor/dan-delvo/excel-import-for-backpack
```

You can then modify the published Blade files without changing the package source.

## Change log

Changes are documented here on Github. Please see the [Releases tab](https://github.com/dan--delvo/excel-import-for-backpack/releases).

## Testing

``` bash
composer test
```

## Contributing

Please see [contributing.md](contributing.md) for a todolist and howtos.

## Security

If you discover any security related issues, please email dandelvo12345@gmail.com instead of using the issue tracker.

## Credits

- [Dan Jaspher Delvo][link-author]
- [All Contributors][link-contributors]

## License

This project was released under MIT, so you can install it on top of any Backpack & Laravel project. Please see the [license file](license.md) for more information. 

However, please note that you do need Backpack installed, so you need to also abide by its [YUMMY License](https://github.com/Laravel-Backpack/CRUD/blob/master/LICENSE.md). That means in production you'll need a Backpack license code. You can get a free one for non-commercial use (or a paid one for commercial use) on [backpackforlaravel.com](https://backpackforlaravel.com).


[ico-version]: https://img.shields.io/packagist/v/dan-delvo/excel-import-for-backpack.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/dan-delvo/excel-import-for-backpack.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/dan-delvo/excel-import-for-backpack
[link-downloads]: https://packagist.org/packages/dan-delvo/excel-import-for-backpack

[link-packagist]: https://packagist.org/packages/dan-delvo/excel-import-for-backpack
[link-downloads]: https://packagist.org/packages/dan-delvo/excel-import-for-backpack
[link-author]: https://github.com/dan--delvo
[link-contributors]: ../../contributors
