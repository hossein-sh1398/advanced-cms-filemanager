@extends('admin.theme.master')


@section('admin-toolbar')
    @include('admin.theme.elements.toolbar', [
        'route' => route('admin.tags.index'),
        'access' => Route::currentRouteName() == 'admin.tags.create' ? 'admin.tags.store' : 'admin.tags.update',
        'type' => true
    ])
@stop
@section('admin-content')
    @include('admin.theme.errors')
    <form id="create_form" method="post"
        action="{{ Route::currentRouteName() == 'admin.tags.create' ? route('admin.tags.store') : route('admin.tags.update', ['tag' => $tag->id]) }}"
        class="form d-flex flex-column flex-lg-row"
    >
        @csrf
        {{ Route::currentRouteName() == 'admin.tags.edit' ? method_field('patch') : '' }}
        <div class="d-flex flex-column flex-row-fluid gap-2 gap-lg-10">
            <div class="d-flex flex-column gap-2 gap-lg-10">
                <div class="card card-flush py-4">
                    <div class="card-body pt-4">
                        <div class="row">
                            <div class="mb-10 fv-row col-md-12">
                                <label class="required form-label">عنوان</label>
                                <input autocomplete="off" type="text" name="title" class="form-control mb-2"
                                    value="{{ old('title', Route::currentRouteName() == 'admin.tags.edit' ? $tag->title : '') }}"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

