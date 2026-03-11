@if ($crud->hasAccess('import'))
    <a href="{{ url($crud->route.'/import') }}" class="btn btn-primary" bp-button="import" data-style="zoom-in">
        <i class="la la-file-import"></i> <span>Import {{ $crud->entity_name_plural }}</span>
    </a>
@endif