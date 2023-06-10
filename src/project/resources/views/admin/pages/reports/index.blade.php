@extends('admin.theme.master')
{{--@section('admin-title')--}}

{{--@stop--}}

@section('admin-toolbar')
    {{-- @include('admin.theme.elements.toolbar') --}}
@endsection
@section('admin-content')
    <input type="hidden" name="report_type" class="query" id="hidden_type" value="{{request()->query('report_type')}}">
    <x-table-component :array="$table" :model="$model" :url="$url">
        <thead>
            <tr class="fw-bolder text-muted">
                <th class="w-25px">
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input form-check-input-all w-15px h-15px"  type="checkbox" value="1" data-kt-check="true" data-kt-check-target=".widget-9-check"/>
                    </div>
                </th>
                <th class="min-w-50px align-center cursor-default">ردیف</th>
                <th class="min-w-150px cursor-pointer sorting" id="sorting-1" onclick="sorting(1)" data-sorting_type="asc" data-column_name="reportable_type">مدل</th>
                <th class="min-w-150px cursor-default">محتوا</th>
                <th class="min-w-150px cursor-pointer table-sort-asc sorting" id="sorting-4" onclick="sorting(4)" data-sorting_type="asc" data-column_name="created_at">تاریخ ایجاد</th>
                <th class="min-w-150px cursor-default">نمایش</th>
                <th class="min-w-100px text-end cursor-default">عملیات</th>
            </tr>
        </thead>
    </x-table-component>
    <div id="box-show-details"></div>
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
    <script>
        $(document).ready(function () {
            let url = $('#hidden_url').val();

            gets(url);
        });
    </script>
@endpush

