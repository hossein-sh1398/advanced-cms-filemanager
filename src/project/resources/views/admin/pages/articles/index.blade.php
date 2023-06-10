@extends('admin.theme.master')
{{--@section('admin-title')--}}

{{--@stop--}}

@section('admin-toolbar')
    <div class="d-flex align-items-center gap-2 gap-lg-3">
        <button type="button" class="btn btn-sm btn-secondary btn-filter btn-block btn-hover-rise p-2 fs-8 "
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
                <input type="hidden" class="query" name="active" id="hidden_active" value="">
                <input type="hidden" class="query" name="special" id="hidden_special" value="">
                <input type="hidden" class="query" name="category" id="hidden_category" value="">
                <div class="mb-10">
                    <label class="form-label fw-semibold">وضعیت:</label>
                    <div>
                        <select class="form-select form-select-filter form-select-solid" id="form-select-filter-active">
                            <option value="" selected>همه</option>
                            <option value="published">منتشر شده</option>
                            <option value="unpublished">منتشر نشده</option>
                        </select>
                    </div>
                </div>
                <div class="mb-10">
                    <label class="form-label fw-semibold">نوع:</label>
                    <div>
                        <select class="form-select form-select-filter form-select-solid" id="form-select-filter-special">
                            <option value="" selected>همه</option>
                            <option value="special" >ویژه</option>
                            <option value="public">معمولی</option>
                        </select>
                    </div>
                </div>
                <div class="mb-10">
                    <label class="form-label fw-semibold">دسته بندی:</label>
                    <div>
                        <select class="form-select form-select-filter form-select-solid" data-control="select2" id="form-select-filter-category-id">
                            <option value="" selected>همه</option>
                            @foreach($categories as $id => $title)
                                <option value="{{$id}}">{{$title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-start">
                    <button type="button" class="btn btn-sm btn-primary" onclick="filterArticles()" data-kt-menu-dismiss="true">
                        اعمال
                    </button>
                    <button type="button" class="btn btn-sm btn-light me-2 btn-clear-filter" onclick="clearFilterArticle()"
                            data-kt-menu-dismiss="true">حذف
                    </button>
                </div>
            </div>
        </div>
    </div>
    @include('admin.theme.elements.toolbar', [
        'route' =>  route('admin.articles.create'),
        'access' => 'admin.articles.create',
        'isCreate' => null,
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
                    data-sorting_type="asc" data-column_name="title">عنوان
                </th>
                <th class="min-w-150px cursor-pointer sorting" id="sorting-2" onclick="sorting(2)"
                    data-sorting_type="asc" data-column_name="active">وضعیت
                </th>
                <th class="min-w-150px cursor-pointer sorting" id="sorting-5" onclick="sorting(5)"
                    data-sorting_type="asc" data-column_name="special">ویژه
                </th>
                <th class="min-w-150px cursor-pointer sorting" id="sorting-3" onclick="sorting(3)"
                    data-sorting_type="asc" data-column_name="category_id">دسته بندی
                </th>
                <th class="min-w-150px cursor-pointer sorting" id="sorting-4" onclick="sorting(4)"
                    data-sorting_type="asc" data-column_name="count">بازدید
                </th>
                <th class="min-w-150px cursor-pointer table-sort-asc sorting" id="sorting-6"
                    onclick="sorting(6)" data-sorting_type="asc" data-column_name="created_at">تاریخ ایجاد
                </th>
                <th class="min-w-150px cursor-default">نمایش</th>
                <th class="min-w-100px text-end cursor-default">عملیات</th>
            </tr>
        </thead>
    </x-table-component>
@stop
@push('admin-css')
@endpush
@push('admin-js')
    @include('admin.theme.errors')
@endpush

