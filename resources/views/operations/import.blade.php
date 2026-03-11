@extends(backpack_view('blank'))

@section('content')
<div class="container-xl">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Bulk Import {{ $crud->entity_name_plural ?? 'Records' }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ url($crud->route.'/import') }}" method="POST" enctype="multipart/form-data" id="import-form">
                @csrf

                <div class="mb-4">
                    <label class="form-label">Step 1: Choose Excel/CSV File</label>
                    <input type="file" name="import_file" id="import_file" class="form-control" accept=".xlsx, .xls, .csv" required>
                    <small class="text-muted">Select a file to map its columns before importing.</small>
                </div>

                <div id="mapping-section" class="mb-4 d-none">
                    <label class="form-label text-primary">Step 2: Map Excel Headers to Database Columns</label>
                    <div class="table-responsive border rounded">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Excel Header</th>
                                    <th>Database Column</th>
                                </tr>
                            </thead>
                            <tbody id="mapping-tbody"></tbody>
                        </table>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                    <i class="la la-cloud-upload"></i> Start Import
                </button>
                <a href="{{ url($crud->route) }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
    document.getElementById('import_file').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (!file) {
            return;
        }

        const reader = new FileReader();
        reader.onload = function (loadEvent) {
            const data = new Uint8Array(loadEvent.target.result);
            const workbook = XLSX.read(data, { type: 'array' });
            const firstSheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[firstSheetName];
            const rows = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

            if (rows.length > 0) {
                generateMappingTable(rows[0]);
                return;
            }

            alert('Could not find any headers in the uploaded file.');
        };

        reader.readAsArrayBuffer(file);
    });

    function generateMappingTable(headers) {
        const tbody = document.getElementById('mapping-tbody');
        const mappingSection = document.getElementById('mapping-section');
        const submitButton = document.getElementById('submit-btn');
        const dbColumns = @json($columns ?? []);

        tbody.innerHTML = '';

        headers.forEach((header) => {
            if (!header) {
                return;
            }

            const row = document.createElement('tr');
            const headerCell = document.createElement('td');
            const selectCell = document.createElement('td');
            const normalizedHeader = header.toLowerCase().replace(/[^a-z0-9]/g, '_');

            headerCell.className = 'fw-bold';
            headerCell.textContent = header;

            let selectHtml = `<select name="mapping[${header}]" class="form-select">`;
            selectHtml += '<option value="">-- Ignore this column --</option>';

            dbColumns.forEach((column) => {
                const selected = normalizedHeader === column || header.toLowerCase() === column.toLowerCase()
                    ? 'selected'
                    : '';

                selectHtml += `<option value="${column}" ${selected}>${column}</option>`;
            });

            selectHtml += '</select>';
            selectCell.innerHTML = selectHtml;

            row.appendChild(headerCell);
            row.appendChild(selectCell);
            tbody.appendChild(row);
        });

        mappingSection.classList.remove('d-none');
        submitButton.removeAttribute('disabled');
    }
</script>
@endpush