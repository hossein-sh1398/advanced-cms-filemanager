@extends('admin.theme.master')
@section('admin-toolbar')
    @include('admin.theme.elements.toolbar', [
        'route' =>  route('admin.article.categories.create'),
        'access' => 'admin.article.categories.create',
        'isCreate' => null,
    ])
@endsection
@section('admin-content')
    <x-table-component :array="$table" :model="$model" :url="$url">
        <thead>
            <tr class="fw-bolder text-muted">
                <th class="w-25px">
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input form-check-input-all w-15px h-15px"  type="checkbox" value="1" data-kt-check="true" data-kt-check-target=".widget-9-check"/>
                    </div>
                </th>
                <th class="min-w-50px align-center cursor-default">ردیف</th>
                <th class="min-w-150px cursor-pointer sorting" id="sorting-1" onclick="sorting(1)" data-sorting_type="asc" data-column_name="title">عنوان</th>
                <th class="min-w-150px cursor-default">ارسال نظر</th>
                <th class="min-w-150px cursor-pointer sorting" id="sorting-2" onclick="sorting(2)" data-sorting_type="asc" data-column_name="parent_id">دسته مادر</th>
                <th class="min-w-150px cursor-default">تعداد زیر دسته</th>
                <th class="min-w-150px cursor-pointer table-sort-asc sorting" id="sorting-3" onclick="sorting(3)" data-sorting_type="asc" data-column_name="created_at">تاریخ ایجاد</th>
                <th class="min-w-150px cursor-default">نمایش</th>
                <th class="min-w-100px text-end cursor-default">عملیات</th>
            </tr>
        </thead>
    </x-table-component>
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
@endpush

