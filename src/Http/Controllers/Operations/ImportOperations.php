<?php

namespace DanDelvo\ExcelImportForBackpack\Http\Controllers\Operations;

use DanDelvo\ExcelImportForBackpack\Imports\GenericImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Prologue\Alerts\Facades\Alert;

trait ImportOperations
{
    /**
     * Defining Routes for this operation
     */
    protected function setupImportRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/import', [
            'as'    => $routeName.'.import',
            'uses'  => $controller.'@getImport',
            'operation' => 'import'
        ]);

        Route::post($segment.'/import', [
            'as'    => $routeName.'.import.upload',
            'uses'  => $controller.'@postImport',
            'operation' => 'import'            
        ]);
    }

    /**
     * Setting default settings and button
     */
    protected function setupImportDefaults()
    {
        $this->crud->allowAccess('import');

        $this->crud->operation('import', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButton('top', 'import', 'view', 'excel-import::buttons.import');
        });
    }

    public function getImport()
    {
        $this->crud->hasAccessOrFail('import');

        $this->data['columns'] = Schema::getColumnListing($this->crud->model->getTable());
        $this->data['crud'] = $this->crud;
        $this->data['title'] = 'Import '.$this->crud->entity_name_plural;

        return view('excel-import::operations.import', $this->data);
    }

    public function postImport(Request $request)
    {
        $this->crud->hasAccessOrFail('import');

        $validated = $request->validate([
            'import_file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
            'mapping' => ['nullable', 'array'],
        ]);

        $mapping = array_filter($validated['mapping'] ?? [], fn ($column) => filled($column));

        if ($mapping === []) {
            Alert::error('Select at least one column mapping before starting the import.')->flash();

            return redirect()->back()->withInput();
        }

        try {
            Excel::import(
                new GenericImport($this->crud->model, $mapping),
                $validated['import_file']
            );

            Alert::success('Import started successfully.')->flash();

            return redirect($this->crud->route);
        } catch (\Throwable $exception) {
            report($exception);

            Alert::error('The import could not be started. '.$exception->getMessage())->flash();

            return redirect()->back()->withInput();
        }
    }

}



