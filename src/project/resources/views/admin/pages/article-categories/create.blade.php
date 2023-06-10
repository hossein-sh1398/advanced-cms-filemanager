@extends('admin.theme.master')


@section('admin-toolbar')
    @include('admin.theme.elements.toolbar', [
        'route' => route('admin.article.categories.index'),
        'access' => checkRoute('admin.article.categories.create') ? 'admin.article.categories.store' : 'admin.article.categories.update',
        'type' => true
    ])
@stop
@section('admin-content')
    <div>
        @include('admin.theme.errors')
        <form id="create_form" method="post" enctype="multipart/form-data"
              action="{{ checkRoute('admin.article.categories.create') ? route('admin.article.categories.store') : route('admin.article.categories.update',  $articleCategory->id) }}"
              class="form d-flex flex-column flex-lg-row"
        >
            @csrf
            {{ checkRoute('admin.article.categories.edit') ? method_field('patch') : '' }}

            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <div class="d-flex flex-column gap-7 gap-lg-10">
                    <div class="card card-flush py-4">
                        <div class="card-body pt-4">
                            <div class="row mb-10 fv-row">
                                <div class="col-md-4">
                                    <label class="required form-label">عنوان</label>
                                    <input autocomplete="off" type="text" name="title" class="form-control mb-2"
                                           value="{{ old('title', checkRoute('admin.article.categories.edit') ? $articleCategory->title : '') }}"
                                    />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">انتخاب سر دسته</label>
                                    <select class="form-select form-select-solid" name="parent_id" data-control="select2"
                                            data-close-on-select="true" data-placeholder="لطفا انتخاب نمایید"
                                            data-allow-clear="true">
                                        <option value="">انتخاب نمایید</option>
                                        @foreach ($articleCategories as $id => $title)
                                            <option value="{{ $id }}"
                                                {{ old('parent_id', checkRoute('admin.article.categories.edit') ? $articleCategory->parent_id : '') == $id ? 'selected' : '' }}
                                            >
                                                {{ $title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">زبان</label>
                                    <select class="form-select form-select-solid" name="lang" data-control="select2"
                                            data-close-on-select="false" data-placeholder="لطفا انتخاب نمایید"
                                            data-allow-clear="true">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-10 fv-row">
                                <div class="col-md-6">
                                    <label class="form-label">توضیحات</label>
                                    <textarea class="form-control resize-none" rows="5" name="content">{{ old('content', checkRoute('admin.article.categories.edit') ? $articleCategory->content : '') }}</textarea>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="image-input image-input-empty image-input-outline mb-3"
                                         data-kt-image-input="true"
                                         @if (checkRoute('admin.article.categories.edit'))
                                            @if ($articleCategory->images->count())
                                                style="background-position: center;background-size: 100% 100%;background-image: url({{url($articleCategory->images->first()->getUrl()) }})"
                                            @else
                                                style="background-image: url({{url($defaultImageUrl)}})"
                                            @endif
                                        @else
                                            style="background-image: url({{url($defaultImageUrl)}})"
                                        @endif
                                         >
                                        <div id="image-input-wrapper" class="image-input-wrapper w-150px h-150px"
                                             style="background-position: center;background-size: 100% 100%;">
                                        </div>
                                        <label
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                            title="انتخاب تصویر">
                                            <i class="bi bi-pencil-fill fs-7"></i>
                                            <input type="file" id="photo" name="photo" accept=".png, .jpg, .jpeg"/>
                                        </label>
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                            title="حذف تصویر">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                            title="حذف تصویر">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input"
                                               @checked(old('comment', checkRoute('admin.article.categories.edit') ? $articleCategory->comment : '')) name="comment"
                                               type="checkbox" value="1"/>
                                        <label class="form-check-label" for="comment">
                                            ارسال نظر
                                        </label>
                                    </div>
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

@push('admin-js')
    {{-- <script>
        const inpFile = document.getElementById('photo');
        inpFile.addEventListener('change', function () {
            const file = this.files[0];
            console.log(file);
            if (file) {
                const reader = new FileReader();
                reader.addEventListener('load', function () {
                    localStorage.setItem('category-image', this.result);
                });
                reader.readAsDataURL(file);
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const recentImageDataUrl = localStorage.getItem('category-image');
            if (recentImageDataUrl) {
                const pre = document.getElementById('image-input-wrapper');
                pre.setAttribute('style', 'background-position: center center; background-size: 100% 100%; background-image: url("' + recentImageDataUrl + '")');

                const inpFile = document.getElementById('photo');
                const myFile = new File([recentImageDataUrl], 'category.jpg', {
                    type: 'image/jpeg',
                    lastModified: new Date(),
                });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(myFile);
                inpFile.files = dataTransfer.files;
            }
        })
    </script> --}}

@endpush

