@extends('admin.theme.master')
@section('admin-toolbar')
    <div class="d-flex align-items-center">
        <a href="#" class="btn btn-sm btn-filter btn-secondary btn-block btn-hover-rise p-2 fs-8" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
            <span class="align-text-bottom mx-1">فیلتر</span>
            <i class="fas fa-filter fs-7"></i>
        </a>
        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_62cfa790dca87"
            >
            <div class="px-7 py-5">
                <div class="fs-5 text-dark fw-bold">گزینه های فیلتر</div>
            </div>
            <div class="separator border-gray-200"></div>
            <div class="px-7 py-5">
                <div class="mb-10">
                    <label class="form-label fw-semibold"> نوع فایل:</label>
                    <div>
                        <select data-control="select2" multiple="multiple" class="form-select form-select-solid" data-kt-select2="true" id="form-select-filter-extensions" data-placeholder="انتخاب نمایید" data-dropdown-parent="#kt_menu_62cfa790dca87" data-allow-clear="true">
                            @foreach ($extensions as $extension)
                                <option value="{{ $extension }}">{{ $extension }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-start">
                    <button type="button" class="btn btn-sm btn-primary" onclick="fileManagerFilter()" data-kt-menu-dismiss="true">
                        اعمال
                    </button>
                    <button type="button" class="btn btn-sm btn-light me-2" onclick="clearfileManagerFilter()"
                        data-kt-menu-dismiss="true">حذف
                    </button>
                </div>
            </div>
        </div>
    </div>
    @can('admin.file.manager.upload')
        <button type="button" class="btn btn-sm btn-secondary btn-block btn-hover-rise p-2 fs-8"
            onclick="modalUpload()">
            <span class="align-text-bottom mx-1">آپلود</span>
            <i class="fas fa-upload fs-7"></i>
        </button>
    @endcan
    @can('admin.file.manager.make.directory')
        <button type="button" class="btn btn-sm btn-success btn-block btn-hover-rise p-2 fs-8"
                data-bs-toggle="modal" data-bs-target="#make-directory-modal">
            <span class="align-text-bottom mx-1">ایجاد دایرکتوری</span>
            <i class="fas fa-plus fs-7"></i>
        </button>
    @endcan
@endsection
@section('admin-content')
    <div class="card card-xl-stretch shadow-sm mb-5 mb-xl-8">
        <div class="card-body py-6">
            <div class="row">
                <div class="col-9">
                    <div class="d-flex gap-3">
                        <div><label class="col-form-label">نمایش محتویات</label></div>
                        <div class="align-self-center ms-2">
                            <select id="page-length" class="form-select form-select-sm form-select-solid" data-hide-search="true" data-control="select2" data-placeholder="Select an option">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div class="align-self-center">
                            <div class="input-group">
                                <input type="text" class="form-control input-file-manager-search" placeholder="جستجو"/>
                                <span class="input-group-text icon-search" style="cursor: pointer">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input full-search-checkbox" type="checkbox" id="full-search-checkbox"/>
                                <label class="form-check-label" for="full-search-checkbox">
                                    جستجوی کامل
                                </label>
                            </div>
                        </div>
                        <div class="badge badge-light-primary ms-5 mt-5 mb-5" id="breadcrumbs" style="direction: rtl">
                            <span class="txt-black" id="path-0"></span>
                        </div>
                    </div>
                    <input type="hidden" class="query" name="query" id="hidden_search" value="">
                    <input type="hidden" class="query" name="page" id="hidden_page" value="1">
                    <input type="hidden" class="query" name="size" id="hidden_page_size" value="10">
                    <input type="hidden" class="query" name="sort" id="hidden_column_name" value="name">
                    <input type="hidden" class="query" name="dir" id="hidden_sort_type" value="desc">
                    <input type="hidden" class="query" name="extension" id="hidden_extension" value="">
                    <input type="hidden" id="hidden_disk" value="{{ config('filesystems.default') }}">
                </div>
                <div class="col-3 text-end align-self-center d-flex justify-content-end gap-3">
                    <div class="me-0 d-none" id="more-actions">
                        <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                            <i class="bi bi-three-dots fs-3"></i>
                        </button>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true">
                            @can("admin.file.manager.multiple.destroy")
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" onclick="deleteRows(event, '{{ route("admin.file.manager.multiple.destroy") }}')">
                                        حذف
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    @include('admin.theme.elements.buttons.excel', ['access' => "admin.file.manager.export"])
                    @include('admin.theme.elements.buttons.csv', ['access' => "admin.file.manager.export"])
                    @include('admin.theme.elements.buttons.pdf', ['access' => "admin.file.manager.export"])
                </div>
            </div>
            @can('admin.file.manager.make.directory')
                <div class="modal fade" tabindex="-1" id="make-directory-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">ایجاد دایرکتوری</h3>
                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                    <span class="svg-icon svg-icon-1"></span>
                                </div>
                                <!--end::Close-->
                            </div>

                            <div class="modal-body">
                                <p>
                                    <input id="form-directory-name" placeholder="نام دایرکتوری" type="text" class="form-control">
                                </p>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light btn-block btn-hover-rise p-2 fs-8" data-bs-dismiss="modal">بستن</button>
                                <button type="button" onclick="makeDirectory('{{ route('admin.file.manager.make.directory') }}')" class="btn btn-sm btn-secondary btn-block btn-hover-rise p-2 fs-8">
                                    <span class="align-text-bottom mx-1">ایجاد</span>
                                    <i class="fas fa-save fs-7"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            @can('admin.file.manager.upload')
                <div class="modal fade" tabindex="-1" id="upload-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">آپلود فایل</h3>

                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                    <span class="svg-icon svg-icon-1"></span>
                                </div>
                                <!--end::Close-->
                            </div>

                            <div class="modal-body modal-body-upload">
                                <div class="dropzone" id="gallery">
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light btn-block btn-hover-rise p-2 fs-8" data-bs-dismiss="modal">بستن</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            <input type="hidden" id="hidden_url" value="{{$url}}">
            <input type="hidden" class="query" name="path" id="hidden_path" value="{{ $path }}">
            <input type="hidden" id="hidden_root" value="{{ $path }}">
            <input type="hidden" id="hidden_start" value="{{ $start }}">
            <input type="hidden" class="query" name="is_full_search" id="hidden_full_search" value="0">
            <div class="table-responsive mt-5">
                <table class="table table-striped table-hover align-middle text-center gs-4 gy-2 gx-2">
                    <thead>
                    <tr class="fw-bolder text-muted">
                        <th class="w-25px">
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input form-check-input-all w-15px h-15px" type="checkbox" value="1" data-kt-check="true" data-kt-check-target=".widget-9-check"/>
                            </div>
                        </th>
                        <th class="min-w-50px align-center cursor-default">ردیف</th>
                        <th class="min-w-200px sorting cursor-pointer table-sort-asc"  id="sorting-1" onclick="sorting(1)" data-sorting_type="asc" data-column_name="name">نام</th>
                        <th class="min-w-150px cursor-default">جدول مدیا</th>
                        <th class="min-w-150px cursor-default">نوع</th>
                        <th class="min-w-150px sorting cursor-pointer table-sort-asc" id="sorting-2" onclick="sorting(2)" data-sorting_type="asc" data-column_name="size">اندازه</th>
                        <th class="min-w-150px cursor-default">پسوند</th>
                        <th class="min-w-150px sorting cursor-pointer table-sort-asc" id="sorting-3" onclick="sorting(3)" data-sorting_type="asc" data-column_name="created_at">تاریخ ایجاد</th>
                        <th class="min-w-100px text-end cursor-default">عملیات</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="row pt-6" id="links">
            </div>
        </div>
    </div>
    @include('admin.pages.file-managers.modal')
@stop
@push('admin-css')
    <style>
        .modal-body {
            text-align: right!important;
        }
    </style>
@endpush
@push('admin-js')
    @include('admin.theme.errors')
    <script src="{{ url('cdn/theme/admin/plugins/custom/fslightbox/fslightbox.bundle.js') }}"></script>

    <script>
        $(document).ready(function() {
            let url = $('#hidden_url').val();

            var myDropzone = new Dropzone("#gallery", {
                url: "{{ route('admin.file.manager.upload') }}?path=" + $('#hidden_path').val(),
                paramName: "file",
                processing : function (file) {
                    this.options.url = "{{ route('admin.file.manager.upload') }}?path=" + $('#hidden_path').val()
                    gets(url)
                },
                maxFilesize: 100,
                addRemoveLinks: true,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                accept: function(file, done) {
                    done();
                },
                success: function (file, res) {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                        toastr.success('آپلود با موفقیت انجام شد');
                    }
                    $('#upload-modal').modal('hide');
                    gets(url)
                },
                error: () => {
                    toastr.error('خطا در آپلود فایل');
                }
            });

            $(function () {
                let basePath = localStorage.getItem('basePath');
                let html = ''
                let root  = $('#hidden_root').val();
                html = `<a href="#" onclick="getContentDirectory(event, '${root}', true)">Root</a>`;
                if (! basePath) {
                    basePath = $('#hidden_path').val();
                }

                    let s = $('#hidden_start').val();

                    if (s) {
                        s = s-1;
                    }

                    let list = basePath.split('/');
                    for(let i = s; i < list.length; i++) {
                        b = '';
                        if (list[i]) {
                            html+= '<span class="fas fa-chevron-left mx-1"></span>';
                            for(let j = 0; j <= i; j++) {

                                b += list[j];
                                if (j < i) {
                                    b += '/';
                                }
                            }
                            if (Number(i) + 1 < list.length) {
                                html += `<a href="#" onclick="getContentDirectory(event, '${b}')">${list[i]}</a>`;
                            } else {
                                html += `<span>${list[i]}</span>`;
                            }
                        }
                    }


                localStorage.setItem('basePath', basePath);
                $('#hidden_path').val(basePath);
                $('#hidden_page').val(1);

                $('#breadcrumbs').html(html);

                gets(url);
            });
        });
    </script>
@endpush
@push('admin-css')
    <style>
        .fa-chevron-right {
            margin: 0 5px;
            color: #142009;
        }
        .txt-black {
            color: black;
        }
        .dropzone .dz-message {
            margin: 0;
            display: flex!important;
            text-align: left;
        }
    </style>
@endpush

