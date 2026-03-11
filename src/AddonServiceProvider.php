<?php

namespace DanDelvo\ExcelImportForBackpack;

use Backpack\CRUD\ViewNamespaces;
use Illuminate\Support\ServiceProvider;

class AddonServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider {
        bootForConsole as protected automaticBootForConsole;
    }

    protected $vendorName = 'dan-delvo';
    protected $packageName = 'excel-import-for-backpack';
    protected $commands = [];

    public function boot(): void
    {
        $this->loadViewsFrom($this->packageViewsPath(), 'excel-import');

        foreach (['buttons', 'columns', 'fields', 'filters', 'widgets'] as $viewNamespace) {
            ViewNamespaces::addFor($viewNamespace, 'excel-import::'.$viewNamespace);
        }

        $this->autoboot();
    }

    protected function packageViewsPath()
    {
        return __DIR__.'/../resources/views';
    }

    protected function bootForConsole(): void
    {
        $this->publishes([
            $this->packageViewsPath() => $this->publishedViewsPath(),
        ], 'excel-import-for-backpack-views');

        $this->publishes([
            $this->packageConfigFile() => $this->publishedConfigFile(),
        ], 'excel-import-for-backpack-config');

        $this->publishes([
            $this->packageViewsPath() => $this->publishedViewsPath(),
            $this->packageConfigFile() => $this->publishedConfigFile(),
        ], 'excel-import-for-backpack');

        $this->automaticBootForConsole();
    }
}



