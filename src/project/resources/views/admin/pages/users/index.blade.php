@extends('admin.theme.master')
@section('admin-toolbar')
<div class="d-flex align-items-center gap-2 gap-lg-3">
    <button type="button" class="btn btn-sm btn-secondary btn-filter btn-block btn-hover-rise p-2 fs-8" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
        <span class="align-text-bottom mx-1">فیلتر</span>
        <i class="fas fa-filter fs-7"></i>
    </button>
    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_62cfa790dca87">
        <div class="px-7 py-5">
            <div class="fs-5 text-dark fw-bold">گزینه های فیلتر</div>
        </div>
        <div class="separator border-gray-200"></div>
        <div class="px-7 py-5">
            <input type="hidden" name="active" class="query" id="hidden_active" value="all">
            <div class="mb-10">
                <label class="form-label fw-semibold">وضعیت:</label>
                <div>
                    <select class="form-select form-select-filter form-select-solid">
                        <option value="all" selected>همه</option>
                        <option value="1">فعال</option>
                        <option value="0">غیر فعال</option>
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-start">
                <button type="button" class="btn btn-sm btn-primary" onclick="usersFilter()" data-kt-menu-dismiss="true">اعمال</button>
                <button type="button" class="btn btn-sm btn-light me-2" onclick="clearFilterUsers()" data-kt-menu-dismiss="true">حذف</button>
            </div>
        </div>
    </div>
</div>
@include('admin.theme.elements.toolbar', [
    'route' =>  route('admin.users.create'),
    'access' => 'admin.users.create',
    'type' => null,
])
@endsection
@section('admin-content')
    <x-table-component :array="$table" :model="$model" :url="$url">
        <x-slot name="more_actions">
            @can('admin.users.change.multiple.status')
                <div class="menu-item px-3">
                    <a href="#" onclick="changeMultiStatus(event, '{{ route('admin.users.change.multiple.status') }}')" class="menu-link flex-stack px-3 d-flex justify-content-between">
                        ویرایش وضعیت
                        <span class="svg-icon svg-icon-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"/>
                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"/> </svg>
                        </span>
                    </a>
                </div>
            @endcan
        </x-slot>
        <thead>
            <tr class="fw-bolder text-muted">
                <th class="w-25px">
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input form-check-input-all w-15px h-15px" type="checkbox" value="1" data-kt-check="true" data-kt-check-target=".widget-9-check"/>
                    </div>
                </th>
                <th class="min-w-50px align-center cursor-default">ردیف</th>
                <th class="min-w-150px cursor-pointer sorting" id="sorting-1" onclick="sorting(1)" data-sorting_type="asc" data-column_name="name">نام</th>
                <th class="min-w-150px cursor-default">وضعیت</th>
                <th class="min-w-150px cursor-pointer sorting" id="sorting-2" onclick="sorting(2)" data-sorting_type="asc" data-column_name="email">ایمیل</th>
                <th class="min-w-150px cursor-pointer sorting" id="sorting-3" onclick="sorting(3)" data-sorting_type="asc" data-column_name="mobile">موبایل</th>
                <th class="min-w-150px cursor-pointer table-sort-asc sorting" id="sorting-4" onclick="sorting(4)" data-sorting_type="asc" data-column_name="created_at">تاریخ ایجاد</th>
                <th class="min-w-150px cursor-default">نمایش</th>
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
    </style>
@endpush
@push('admin-js')
    @include('admin.theme.errors')
@endpush

