@extends('admin.theme.master')
@section('admin-toolbar')
    @include('admin.theme.elements.toolbar', [
        'route' => route('admin.configs.index'),
        'access' => checkRoute('admin.configs.edit') ? 'admin.configs.update' : 'admin.configs.store',
        'type' => true
    ])
@stop
@section('admin-content')
    <div>
        @include('admin.theme.errors')
        <form id="create_form" method="post"
            @if(checkRoute('admin.configs.edit'))
                action="{{route('admin.configs.update', ['config' => $config->id])}}"
            @else
                action="{{route('admin.configs.store')}}"
            @endif
            class="form d-flex flex-column flex-lg-row">
            {{ checkRoute('admin.configs.edit') ? method_field('patch')  : '' }}
            @csrf

            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-flush py-4">
                        <div class="card-body pt-4">
                            <div class="row mb-10">
                                <div class="col-12">
                                    <label class="form-label">کلید:</label>
                                    <input type="text" name="main_key" value="{{ old('key', checkRoute('admin.configs.edit') ? $config->key : '')  }}" class="form-control mb-2 mb-md-0"/>
                                </div>
                            </div>
                            <div class="row mb-10" >
                                <!--begin::Repeater-->
                                <div id="configs">
                                    <!--begin::Form group-->
                                    <div class="form-group">
                                        <div data-repeater-list="value">
                                            @if(checkRoute('admin.configs.edit'))
                                                @if ($config->num == 1)
                                                    <div data-repeater-item>
                                                        <div class="form-group row mb-5">
                                                            <div class="col-md-3">
                                                                <label class="form-label">کلید:</label>
                                                                <input type="text" name="key" class="form-control mb-2 mb-md-0"/>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="inner-repeater">
                                                                    <div data-repeater-list="values" class="mb-5">
                                                                        <div data-repeater-item>
                                                                            <label class="form-label">مقدار:</label>
                                                                            <div class="input-group pb-3">
                                                                                <input type="text" value="{{ $config->value }}" name="value" class="form-control" placeholder="مقدار را وارد نمایید" />
                                                                                <button  class="border border-secondary btn btn-icon btn-light-danger" data-repeater-delete type="button">
                                                                                    <i class="la la-trash-o fs-3"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button class="btn btn-sm btn-light-primary" data-repeater-create type="button">
                                                                        <i class="la la-plus"></i> افزودن مقدار
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-9">
                                                                    <i class="la la-trash-o fs-3"></i>حذف سطر
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif ($config->num == 2)
                                                    @foreach ($config->value as  $value)
                                                        <div data-repeater-item>
                                                            <div class="form-group row mb-5">
                                                                <div class="col-md-3">
                                                                    <label class="form-label">مقدار کلید:</label>
                                                                    <input type="text" name="key" class="form-control mb-2 mb-md-0" placeholder="مقدار کلید را وارد نمایید" />

                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="inner-repeater">
                                                                        <div data-repeater-list="values">

                                                                                <div data-repeater-item>
                                                                                    <label class="form-label">مقدار:</label>
                                                                                    <div class="input-group pb-3">
                                                                                        <input type="text" value="{{ $value }}" name="value" class="form-control" placeholder="مقدار را وارد نمایید" />
                                                                                        <button  class="border border-secondary btn btn-icon btn-light-danger" data-repeater-delete type="button">
                                                                                            <i class="la la-trash-o fs-3"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>

                                                                        </div>
                                                                        <button class="btn btn-sm btn-light-primary" data-repeater-create type="button">
                                                                            <i class="la la-plus"></i> افزودن مقدار
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-9">
                                                                        <i class="la la-trash-o fs-3"></i>حذف سطر
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    @foreach ($config->value as $value1)
                                                        @foreach ($value1 as $key => $value)
                                                        <div data-repeater-item>
                                                            <div class="form-group row mb-5">
                                                                <div class="col-md-3">
                                                                    <label class="form-label">مقدار کلید:</label>
                                                                    <input type="text" name="key" value="{{ $key }}" class="form-control mb-2 mb-md-0" placeholder="مقدار کلید را وارد نمایید" />
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="inner-repeater">
                                                                        <div data-repeater-list="values">
                                                                            @foreach ($value as $v)
                                                                                <div data-repeater-item>
                                                                                    <label class="form-label">مقدار:</label>
                                                                                    <div class="input-group pb-3">
                                                                                        <input type="text" value="{{ $v }}" name="value" class="form-control" placeholder="مقدار را وارد نمایید" />
                                                                                        <button  class="border border-secondary btn btn-icon btn-light-danger" data-repeater-delete type="button">
                                                                                            <i class="la la-trash-o fs-3"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                        <button class="btn btn-sm btn-light-primary" data-repeater-create type="button">
                                                                            <i class="la la-plus"></i> افزودن مقدار
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-9">
                                                                        <i class="la la-trash-o fs-3"></i>حذف سطر
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    @endforeach
                                                @endif
                                            @else
                                                <div data-repeater-item>
                                                    <div class="form-group row mb-5">
                                                        <div class="col-md-3">
                                                            <label class="form-label">مقدار کلید:</label>
                                                            <input type="text" name="key" class="form-control mb-2 mb-md-0" placeholder="مقدار کلید را وارد نمایید" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="inner-repeater">
                                                                <div data-repeater-list="values" class="mb-5">
                                                                    <div data-repeater-item>
                                                                        <label class="form-label">مقدار:</label>
                                                                        <div class="input-group pb-3">
                                                                            <input type="text" name="value" class="form-control" placeholder="مقدار را وارد نمایید" />
                                                                            <button  class="border border-secondary btn btn-icon btn-light-danger" data-repeater-delete type="button">
                                                                                <i class="la la-trash-o fs-3"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <button class="btn btn-sm btn-light-primary" data-repeater-create type="button">
                                                                    <i class="la la-plus"></i> افزودن مقدار
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-9">
                                                                <i class="la la-trash-o fs-3"></i>حذف سطر
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <!--end::Form group-->
                                    <!--begin::Form group-->
                                    <div class="form-group">
                                        <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                                            <i class="la la-plus"></i>افزودن سطر
                                        </a>
                                    </div>
                                    <!--end::Form group-->
                                </div>
                                <!--end::Repeater-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@push('admin-css')
    <style></style>
@endpush

@push('admin-js')
<script src="{{ url('cdn/theme/admin/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script>
        $("#configs").repeater({
    repeaters: [{
        selector: '.inner-repeater',
        show: function () {
            $(this).slideDown();
        },

        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    }],

    show: function () {
        $(this).slideDown();
    },

    hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
    }
});
    </script>
@endpush

