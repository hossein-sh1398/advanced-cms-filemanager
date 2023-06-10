@extends('admin.theme.master')
@section('admin-title')
    لیست مشترکین خبرنامه سایت
@stop

@section('admin-toolbar')
    {{-- <div class="d-flex align-items-center gap-2 gap-lg-3">
        <button type="button" class="btn btn-sm btn-secondary btn-block btn-hover-rise p-2 fs-8"
                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
            <span class="align-text-bottom mx-1">فیلتر</span>
            <i class="fas fa-filter fs-7"></i>
        </button>
        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_62cfa790dca87"
             style="">
            <div class="px-7 py-5">
                <div class="fs-5 text-dark fw-bold">گزینه های فیلتر</div>
            </div>
            <div class="separator border-gray-200"></div>
            <div class="px-7 py-5">
                <div class="mb-10">
                    <label class="form-label fw-semibold">وضعیت برسی:</label>
                    <div>
                        <select class="form-select form-select-filter form-select-solid" id="form-select-filter-read">
                            <option value="unread">خوانده نشده</option>
                            <option value="">همه</option>
                            <option value="read">خوانده شده</option>
                        </select>
                    </div>
                </div>
                <div class="mb-10">
                    <label class="form-label fw-semibold">وضعیت حذف:</label>
                    <div>
                        <select class="form-select form-select-filter form-select-solid"
                                id="form-select-filter-soft-delete">
                            <option value="">حذف نشده</option>
                            <option value="withTrashed">همه</option>
                            <option value="trashed">حذف شده</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-start">
                    <button type="button" class="btn btn-sm btn-primary" onclick="filter()" data-kt-menu-dismiss="true">
                        اعمال
                    </button>
                    <button type="button" class="btn btn-sm btn-light me-2" onclick="clearFilter()"
                            data-kt-menu-dismiss="true">حذف
                    </button>
                </div>
            </div>
        </div>
    </div> --}}
    @include('admin.theme.elements.toolbar', [
        'route' => route("admin.news.letters.create"),
        'access' => 'admin.news.letters.create',
        'type' => null,
    ])
@endsection
@section('admin-content')
<x-table-component :array="$table" :model="$model" :url="$url">
    <thead>
        <tr class="fw-bolder text-muted">
            <th class="w-25px">
                <div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input form-check-input-all w-15px h-15px" type="checkbox"
                        value="1" data-kt-check="true" data-kt-check-target=".widget-9-check"/>
                </div>
            </th>
            <th class="min-w-50px align-center cursor-default">ردیف</th>
            <th class="min-w-150px cursor-pointer sorting" id="sorting-1" onclick="sorting(1)"
                data-sorting_type="asc" data-column_name="email">ایمیل
            </th>
            <th class="min-w-150px cursor-pointer sorting" id="sorting-3" onclick="sorting(3)"
                data-sorting_type="asc" data-column_name="active_email">تایید ایمیل
            </th>
            <th class="min-w-150px cursor-pointer sorting" id="sorting-4" onclick="sorting(4)"
                data-sorting_type="asc" data-column_name="active_mobile">تایید موبایل
            </th>
            <th class="min-w-150px cursor-pointer sorting" id="sorting-2" onclick="sorting(2)"
                data-sorting_type="asc" data-column_name="mobile">شماره موبایل
            </th>
            <th class="min-w-150px cursor-pointer table-sort-asc sorting" id="sorting-5"
                onclick="sorting(5)" data-sorting_type="asc" data-column_name="created_at">تاریخ ارسال
            </th>
            <th class="min-w-100px text-end cursor-default">عملیات</th>
        </tr>
    </thead>
</x-table-component>
@stop
@push('admin-css')
    <style>
        .modal-body {
            text-align: right !important;
        }

        td, th {
            text-align: center !important;
        }

        .form-label {
            font-weight: 700;
            padding-left: 5px;
        }
    </style>
@endpush
@push('admin-js')
    @include('admin.theme.errors')
@endpush

