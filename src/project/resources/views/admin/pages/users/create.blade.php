@extends('admin.theme.master')


@section('admin-toolbar')
    @include('admin.theme.elements.toolbar', [
        'route' => route('admin.users.index'),
        'access' => checkRoute('admin.users.create') ? 'admin.users.store' : 'admin.users.update',
        'type' => true
    ])
@stop
@section('admin-content')
@include('admin.theme.errors')
    <form id="create_form" method="post"
        action="{{ checkRoute('admin.users.create') ? route('admin.users.store') : route('admin.users.update', ['user' => $user->id]) }}"
        class="form d-flex flex-column flex-lg-row"
    >
        @csrf
        {{ checkRoute('admin.users.edit') ? method_field('patch') : '' }}
        <input type="hidden" name="save_type" id="save_type">
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="d-flex flex-column gap-7 gap-lg-10">
                <div class="card card-flush py-4">
                    <div class="card-body pt-4">
                        <div class="row">
                            <div class="mb-10 fv-row col-md-4">
                                <label class="required form-label">نام</label>
                                <input autocomplete="off" type="text" name="name" class="form-control mb-2"
                                    value="{{ old('name', checkRoute('admin.users.edit') ? $user->name : '') }}"
                                />
                            </div>
                            <div class="mb-10 fv-row col-md-4">
                                <label class="required form-label">ایمیل</label>
                                <input autocomplete="off" type="email" dir="ltr" name="email" class="form-control mb-2"
                                value="{{ old('email', checkRoute('admin.users.edit') ? $user->email : '') }}" />
                            </div>
                            <div class="mb-10 fv-row col-md-4">
                                <label class="required form-label">شماره موبایل</label>
                                <input autocomplete="off" type="text" dir="ltr" name="mobile" class="form-control mb-2" value="{{ old('mobile', Route::currentRouteName() == 'admin.users.edit' ? $user->mobile : '') }}" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="{{ checkRoute('admin.users.edit') ? 'col-md-6' : 'col-md-4'}} {{ checkRoute('admin.users.edit') && $disabled ? 'd-none' : ''}}">
                                <label class="form-label required">انتخاب دسترسی</label>
                                <select  class=" form-select form-select-solid" name="roles[]" data-control="select2" data-close-on-select="false" data-placeholder="لطفا انتخاب نمایید" data-allow-clear="true" multiple="multiple">
                                    <option></option>
                                    @foreach ($roles as $id => $name)
                                        <option value="{{ $id }}"
                                            {{ in_array($id, old('roles', checkRoute('admin.users.edit') ? $user->roles->pluck('id')->toArray() : [])) ? 'selected' : '' }}
                                        >
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-10 fv-row {{ checkRoute('admin.users.edit') ? 'col-md-6' : 'col-md-4'}}">
                                <label class="{{ checkRoute('admin.users.create') ? 'required' : ''}} form-label">رمز عبور</label>
                                <input autocomplete="off" type="password" dir="ltr" name="password" class="form-control mb-2"/>
                            </div>
                            @if (checkRoute('admin.users.create'))
                                <div class="mb-10 fv-row col-md-4">
                                    <label class="required form-label">تایید رمز عبور</label>
                                    <input autocomplete="off" type="password" dir="ltr" name="password_confirmation" class="form-control mb-2"/>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@push('admin-css')
    <style>
        .select2-container--bootstrap5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice .select2-selection__choice__remove {
            left: 5px;
        }
    </style>
@endpush

