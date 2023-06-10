@can($access)
    <button onclick="exported(event, 'csv', '{{ route($access) }}')" class="btn btn-light p-2 fs-8">
        <span class="align-text-bottom text-info">CSV</span>
        <i class="fas fa-file-csv fs-7 text-info"></i>
    </button>
@endcan
