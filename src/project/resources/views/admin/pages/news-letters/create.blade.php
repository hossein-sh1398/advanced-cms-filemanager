@extends('admin.theme.master')
@section('admin-toolbar')
    @include('admin.theme.elements.toolbar', [
        'route' => route('admin.news.letters.index'),
        'access' => Route::currentRouteName() == 'admin.news.letters.edit' ? 'admin.news.letters.update' : 'admin.news.letters.store',
        'type' => true
    ])
@stop
@section('admin-content')
    <div>
        @include('admin.theme.errors')
        <form id="create_form" method="post" class="form d-flex flex-column flex-lg-row"
            action="{{Route::currentRouteName() == 'admin.news.letters.edit' ? route('admin.news.letters.update', ['newsLetter' => $newsLetter->id]) : route('admin.news.letters.store')}}"
            >
            @csrf
            {{ Route::currentRouteName() == 'admin.news.letters.edit' ? method_field('patch') : '' }}
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-flush py-4">
                        <div class="card-body pt-4">
                            <div class="row">
                                <div class="mb-10 fv-row col-6">
                                    <label class="form-label">ایمیل</label>
                                    <input autocomplete="off" type="email" dir="ltr" name="email" class="form-control mb-2" value="{{ old('email', Route::currentRouteName() == 'admin.news.letters.edit' ? $newsLetter->email : '') }}" />
                                </div>
                                <div class="mb-10 fv-row col-6">
                                    <label class="form-label">شماره موبایل</label>
                                    <input autocomplete="off" type="text" dir="ltr" name="mobile" class="form-control mb-2" value="{{ old('mobile', Route::currentRouteName() == 'admin.news.letters.edit' ? $newsLetter->mobile : '') }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@push('admin-css')
    <style>
        .select2-container--bootstrap5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice .select2-selection__choice__remove {
            left: 5px;
        }
    </style>
@endpush

