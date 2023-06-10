@extends('admin.theme.master')


@section('admin-toolbar')
    @include('admin.theme.elements.toolbar', [
        'route' => route('admin.permissions.index'),
        'access' =>  Route::currentRouteName() == 'admin.permissions.create' ? 'admin.permissions.store' : 'admin.permissions.update',
        'type' => true
    ])
@stop
@section('admin-content')
    @include('admin.theme.errors')
    <form id="create_form" method="post"
        action="{{ Route::currentRouteName() == 'admin.permissions.create' ? route('admin.permissions.store') : route('admin.permissions.update', ['permission' => $permission->id]) }}"
        class="form d-flex flex-column flex-lg-row"
    >
        @csrf
        {{ Route::currentRouteName() == 'admin.permissions.edit' ? method_field('patch') : '' }}

        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="d-flex flex-column gap-7 gap-lg-10">
                <div class="card card-flush py-4">
                    <div class="card-body pt-4">
                        <div class="row">
                            <div class="mb-10 fv-row col-md-6">
                                <label class="required form-label">نام</label>
                                <input autocomplete="off" type="text" name="name" class="form-control mb-2"
                                    value="{{ old('name', Route::currentRouteName() == 'admin.permissions.edit' ? $permission->name : '') }}"
                                />
                            </div>
                            <div class="mb-10 fv-row col-md-6">
                                <label class="required form-label">گروه</label>
                                <input autocomplete="off" type="text" name="group" class="form-control mb-2"
                                    value="{{ old('group', Route::currentRouteName() == 'admin.permissions.edit' ? $permission->group : '') }}"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

