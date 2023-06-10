@can($access)
    <button onclick="exported(event, 'excel', '{{ route($access) }}')" class="btn btn-light btn-sm p-2 fs-8">
        <span class="align-text-bottom text-info">Excel</span>
        <i class="fas fa-file-excel fs-7 text-info"></i>
    </button>
@endcan
