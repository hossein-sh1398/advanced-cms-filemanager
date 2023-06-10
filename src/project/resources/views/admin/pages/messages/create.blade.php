@extends('admin.theme.master')
@section('admin-toolbar')
    @include('admin.theme.elements.toolbar', [
        'route' => route('admin.messages.index'),
        'access' => 'admin.messages.store',
        'type' => true
    ])
@stop
@section('admin-content')
    <div>
        @include('admin.theme.errors')
        <form id="create_form" method="post" action="{{route('admin.messages.store')}}" class="form d-flex flex-column flex-lg-row">
            @csrf
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-flush py-4">
                        <div class="card-body pt-4">
                            <div class="row">
                                <div class="mb-10 fv-row col-md-4">
                                    <label class="required form-label">نام</label>
                                    <input autocomplete="off" type="text" name="name" class="form-control mb-2" value="{{ old('name') }}" />
                                </div>
                                <div class="mb-10 fv-row col-md-4">
                                    <label class="required form-label">ایمیل</label>
                                    <input autocomplete="off" type="email" dir="ltr" name="email" class="form-control mb-2" value="{{ old('email') }}" />
                                </div>
                                <div class="mb-10 fv-row col-md-4">
                                    <label class="required form-label">شماره موبایل</label>
                                    <input autocomplete="off" type="text" dir="ltr" name="mobile" class="form-control mb-2" value="{{ old('mobile') }}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-10 fv-row col-md-12">
                                    <label class="required form-label">پیام شما</label>
                                    <textarea name="content" cols="30" rows="3" style="resize: none" class="form-control mb-2"></textarea>
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

