@can($access)
    <button onclick="exported(event, 'pdf', '{{ route($access) }}')" class="btn btn-light p-2 fs-8">
        <span class="align-text-bottom text-info">PDF</span>
        <i class="fas fa-file-pdf fs-7 text-info"></i>
    </button>
@endcan
