@extends('admin.theme.master')
@push('admin-css')
@endpush
@section('admin-toolbar')
   @include('admin.theme.elements.toolbar')
@stop
@section('admin-content')
    <div class="card card-xl-stretch shadow-sm mb-5 mb-xl-8">
        <!--begin::Body-->
        <div class="card-body py-6">
            <div class="row">
                <div class="col-6">
                    <div class="input-group">
                        <div>
                            <label class="col-form-label">نمایش محتویات</label>
                        </div>
                        <div class="align-self-center ms-2">
                            <select class="form-select form-select-sm form-select-solid" data-hide-search="true"
                                    onchange="redirect('per_page', this)" data-control="select2"
                                    data-placeholder="Select an option">
                                <option {{ $perPage == 5 ? 'selected' : ''  }} value="5">5</option>
                                <option {{ $perPage == 10 ? 'selected' : ''  }} value="10">10</option>
                                <option {{ $perPage == 20 ? 'selected' : ''  }} value="20">20</option>
                                <option {{ $perPage == 50 ? 'selected' : ''  }} value="50">50</option>
                                <option {{ $perPage == 100 ? 'selected' : ''  }} value="100">100</option>
                            </select>
                        </div>
                        <div class="align-self-center">
                            <div class="input-group input-group-sm ms-3">
                                <input class="form-control form-control-sm" type="text" id="search"
                                       onblur="redirect('search', this)"
                                       value="{{ $search ?? '' }}"
                                       placeholder="جستجو ...">
                                <span class="input-group-text" onclick="searchClear()">
                                    <i class="fas fa-times-circle text-hover-danger"></i>
                                </span>
                            </div>
                        </div>
                        @yield('admin-table-toolbar-right')
                    </div>

                </div>
                @yield('admin-table-toolbar-left')
                {{--<div class="col-6 text-end align-self-center">
                    <button class="btn btn-light p-2 fs-8">
                        <span class="align-text-bottom text-info">Excel</span>
                        <i class="fas fa-file-excel fs-7 text-info"></i>
                    </button>
                    <button class="btn btn-light p-2 fs-8">
                        <span class="align-text-bottom text-info">CSV</span>
                        <i class="fas fa-file-csv fs-7 text-info"></i>
                    </button>
                    <button class="btn btn-light p-2 fs-8">
                        <span class="align-text-bottom text-info">PDF</span>
                        <i class="fas fa-file-pdf fs-7 text-info"></i>
                    </button>
                </div>--}}
            </div>
            @yield('admin-table-content')
        </div>
        <!--begin::Body-->
    </div>
@stop
@push('admin-js')
    @include('admin.theme.errors')
    <script>
        function update_query_parameters(key, val, url = false) {
            let uri = url === false ? window.location.href : url
            return uri.replace(RegExp("([?&]" + key + "(?=[=&#]|$)[^#&]*|(?=#|$))"), "&" + key + "=" + encodeURIComponent(val))
                .replace(/^([^?&]+)&/, "$1?");
        }

        function redirect(type, data) {
            let field = data.value === undefined ? data : data.value
            window.location.href = update_query_parameters(type, field);
        }

        function sort(data) {
            let dir = $(data).data('dir')
            let field = $(data).data('value')
            let uri = update_query_parameters('sort', field);
            window.location.href = update_query_parameters('dir', dir, uri);
        }

        function searchClear() {
            window.location.href = update_query_parameters('search', '');
        }

        function deleteConfirm(id) {
            Swal.fire({
                title: "آیا از عمل مطمئن هستید؟",
                text: "انجام این کار ممکن است زمان زیادی طول بکشد",
                icon: "error",
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: "بلی",
                cancelButtonText: 'خیر',
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: 'btn btn-light'
                },
                preConfirm: function (isConfirmStatus) {
                    if (isConfirmStatus) $("#deleteItem" + id).submit();
                }
            });
        }
    </script>
@endpush