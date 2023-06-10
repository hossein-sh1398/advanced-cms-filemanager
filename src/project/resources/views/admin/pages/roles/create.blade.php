@extends('admin.theme.master')


@section('admin-toolbar')
    @include('admin.theme.elements.toolbar', [
        'route' => route('admin.roles.index'),
        'access' => Route::currentRouteName() == 'admin.roles.create' ? 'admin.roles.store' : 'admin.roles.update',
        'type' => true
    ])
@stop
@section('admin-content')
@include('admin.theme.errors')
    <form id="create_form" method="post"
        action="{{ Route::currentRouteName() == 'admin.roles.create' ? route('admin.roles.store') : route('admin.roles.update', ['role' => $role->id]) }}"
        class="form d-flex flex-column flex-lg-row"
    >
        @csrf
        {{ Route::currentRouteName() == 'admin.roles.edit' ? method_field('patch') : '' }}

        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="d-flex flex-column gap-7 gap-lg-10">
                <div class="card card-flush py-4">
                    <div class="card-body pt-4">
                        <div class="row">
                            <div class="mb-10 fv-row col-md-6">
                                <label class="required form-label">نام</label>
                                <input dir="ltr" autocomplete="off" type="text" name="name" class="form-control mb-2"
                                    value="{{ old('name', Route::currentRouteName() == 'admin.roles.edit' ? $role->name : '') }}"
                                />
                            </div>
                            <div class="mb-10 fv-row col-md-6">
                                <label class="required form-label">نام فارسی</label>
                                <input autocomplete="off" type="text" name="persian_name" class="form-control mb-2"
                                    value="{{ old('persian_name', Route::currentRouteName() == 'admin.roles.edit' ? $role->persian_name : '') }}"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

