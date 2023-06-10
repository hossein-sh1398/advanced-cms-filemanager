@extends('admin.theme.master')

@section('admin-toolbar')
    @include('admin.theme.elements.toolbar', [
        'route' => route('admin.articles.index'),
        'access' =>  checkRoute('admin.articles.create') ? 'admin.articles.store' : 'admin.articles.update',
        'type' => true
    ])
@stop

@section('admin-content')
    @include('admin.theme.errors')
    <form id="create_form"
            method="post"
            enctype="multipart/form-data"
            action="{{ checkRoute('admin.articles.create') ? route('admin.articles.store') : route('admin.articles.update', ['article' => $article->id]) }}"
            class="form d-flex flex-column flex-lg-row"
    >
        @csrf
        {{ checkRoute('admin.articles.edit') ? method_field('patch')  : '' }}
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="required">دسته بندی</h5>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <select class="form-select" data-control="select2" name="category_id">
                        <option disabled selected="selected">لطفا دسته را انتخاب نمایید</option>
                        @foreach ($articleCategories as $id => $title)
                            <option value="{{ $id }}"
                                {{ old('category_id', checkRoute('admin.articles.edit') ? $article->category_id  : '') == $id ? 'selected' : '' }}
                            >
                                {{ $title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h5>وضعیت</h5>
                    </div>
                    <div class="card-toolbar">
                        <div class="rounded-circle {{ checkRoute('admin.articles.edit') && $article->active ? 'bg-success' : 'bg-danger'}}  w-15px h-15px" id="kt_ecommerce_add_product_status"></div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <select class="form-select" name="active" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="kt_ecommerce_add_product_status_select">
                        <option></option>
                        <option value="1" {{ old('active', checkRoute('admin.articles.edit') ? $article->active : '') ? 'selected' : '' }}>فعال</option>
                        <option value="0" {{ !old('active', checkRoute('admin.articles.edit') ? $article->active : '') ? 'selected' : '' }}>غیر فعال</option>
                    </select>
                </div>
            </div>
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h5>تاریخ انتشار</h5>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <input class="form-control" data-jdp value="{{ old('published_at', checkRoute('admin.articles.edit') ? $article->published_at : '') }}" autocomplete="off" type="text" name="published_at">
                </div>
            </div>
            <div class="card card-flush">
                <div class="card-header justify-content-end">
                    <div class="card-toolbar">
                        <span class="btn btn-icon {{ checkRoute('admin.articles.edit') && $article->special ? 'btn-color-warning' : 'btn-color-gray' }} btn-sm">
                            <span class="svg-icon svg-icon-1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.1359 4.48359C11.5216 3.82132 12.4784 3.82132 12.8641 4.48359L15.011 8.16962C15.1523 8.41222 15.3891 8.58425 15.6635 8.64367L19.8326 9.54646C20.5816 9.70867 20.8773 10.6186 20.3666 11.1901L17.5244 14.371C17.3374 14.5803 17.2469 14.8587 17.2752 15.138L17.7049 19.382C17.7821 20.1445 17.0081 20.7069 16.3067 20.3978L12.4032 18.6777C12.1463 18.5645 11.8537 18.5645 11.5968 18.6777L7.69326 20.3978C6.99192 20.7069 6.21789 20.1445 6.2951 19.382L6.7248 15.138C6.75308 14.8587 6.66264 14.5803 6.47558 14.371L3.63339 11.1901C3.12273 10.6186 3.41838 9.70867 4.16744 9.54646L8.3365 8.64367C8.61089 8.58425 8.84767 8.41222 8.98897 8.16962L11.1359 4.48359Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" value="1" type="checkbox" name="special" @checked(old('special', checkRoute('admin.articles.edit') ? $article->special : ''))>
                        <label class="form-check-label" for="flexCheckDefault">
                            <h5>ویژه</h5>
                        </label>
                    </div>
                </div>
            </div>
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="required">تصویر شاخص</h5>
                    </div>
                </div>
                <div class="card-body text-center pt-0">
                    <div class="image-input image-input-empty image-input-outline mb-3 object-fit-cover" data-kt-image-input="true"
                        @if (checkRoute('admin.articles.edit') && $article->specificImage->first())
                            style="background-position: center;background-size: 100% 100%;background-image: url({{url($article->specificImage->first()->getUrl()) }})"
                        @endif
                    >
                        <div id="image-input-wrapper" class="image-input-wrapper w-150px h-150px" style="background-position: center;background-size: 100% 100%;">

                        </div>
                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="انتخاب تصویر">
                            <i class="bi bi-pencil-fill fs-7"></i>
                            <input type="file" id="photo" name="photo" accept=".png, .jpg, .jpeg" />
                        </label>
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="حذف تصویر">
                            <i class="bi bi-x fs-2"></i>
                        </span>
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="حذف تصویر">
                            <i class="bi bi-x fs-2"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h5>سئو</h5>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <label class="form-label">توضیحات کوتاه</label>
                    <textarea class="form-control mb-2" rows="4" style="resize: none"  name="meta_description">{{ old('meta_description', checkRoute('admin.articles.edit') ? $article->meta_description : '') }}</textarea>
                    <label class="form-label d-block">کلمات کلیدی</label>
                    <input class="form-control form-control-solid" id="tags" value="{{ old('tags', checkRoute('admin.articles.edit') ? join(',', $article->tags->pluck('title')->toArray()) : '') }}" name="tags"/>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x fs-6 mb-n2">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_ecommerce_add_product_general">عمومی</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="card card-flush py-4">
                            <div class="card-body pt-4">
                                <div class="mb-10 fv-row">
                                    <label class="required form-label">عنوان</label>
                                    <input type="text" name="title" class="form-control mb-2" placeholder="عنوان مقاله" value="{{ old('title', checkRoute('admin.articles.edit') ? $article->title : '') }}" />
                                </div>
                                <div>
                                    <label class="required form-label">توضیحات</label>
                                        <textarea class="form-control" name="content" id="content" cols="30" rows="10">{{ old('content', checkRoute('admin.articles.edit') ? $article->content : '') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>ویدیو</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="fv-row mb-2">
                                    <div class="dropzone" id="video">
                                        <div class="dz-message needsclick">
                                            <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
                                            <div class="ms-4">
                                                <h3 class="fs-5 fw-bolder text-gray-900 mb-1">فایل ها را اینجا رها کنید یا برای آپلود کلیک کنید.</h3>
                                                <span class="fs-7 fw-bold text-gray-400">حداکثر 10 فایل را آپلود کنید</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if (checkRoute('admin.articles.edit'))
                                        <div class="row mt-3">
                                            @foreach($article->videos as $video)
                                                <div class="col-md-4 mb-3">
                                                    <div class="article-media">
                                                        <button type="button"
                                                                onclick="deleteMedia({url: '{{ route('admin.articles.delete.media', ['media' => $video->id]) }}'})"
                                                                title="حذف ویدیو"
                                                                class="btn btn-danger btn-sm"
                                                        >
                                                            حذف</button>
                                                        <video controls>
                                                            <source src="{{ url($video->getUrl()) }}" type="video/{{ $video->mime_type }}">
                                                        </video>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-7">
                                    <p>ویدیوی موقت</p>
                                    <div class="row" id="video-list">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>گالری</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="fv-row mb-2">
                                    <div class="dropzone" id="gallery">
                                        <div class="dz-message needsclick">
                                            <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
                                            <div class="ms-4">
                                                <h3 class="fs-5 fw-bolder text-gray-900 mb-1">فایل ها را اینجا رها کنید یا برای آپلود کلیک کنید.</h3>
                                                <span class="fs-7 fw-bold text-gray-400">حداکثر 10 فایل را آپلود کنید</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if (checkRoute('admin.articles.edit'))
                                        @if ($article->images->isNotEmpty())
                                            <div class="row mt-7">
                                                @foreach($article->images as $image)
                                                    <div class="col-md-3 mb-3">
                                                        <div class="article-media">
                                                            <button type="button"
                                                                onclick="deleteMedia({url: '{{ route('admin.articles.delete.media', ['media' => $image->id]) }}'})"
                                                                title="حذف تصویر" class="btn btn-danger btn-sm">حذف</button>
                                                            <a href="{{url($image->getUrl()) }}" target="_blank">
                                                                <img src="{{url($image->getUrl()) }}" alt="{{ $image->name }}">
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                    <div class="mt-7">
                                        <p>گالری موقت</p>
                                        <div class="row" id="gallery-list">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@push('admin-css')
    <link type="text/css" rel="stylesheet" href="{{ url('cdn/theme/admin/css/jalalidatepicker.min.css') }}" />
    @endpush

    @push('admin-js')
    <script type="text/javascript" src="{{ url('cdn/theme/admin/js/jalalidatepicker.min.js')}}"></script>
    <script src="//cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
    <script>
        jalaliDatepicker.startWatch({
            time:true,
            separatorChars:{
                date:"-",
                time:":",
                between:" "
            }
        });

        CKEDITOR.replace('content', {
            filebrowserUploadUrl: "{{route('admin.articles.ck.upload', ['_token' => csrf_token(), 'id' => checkRoute('admin.articles.edit') ? $article->id : 0])}}",
            filebrowserUploadMethod: 'form'
        });


        var input1 = document.querySelector("#tags");

        let list = [];
        @foreach($tags as $key => $tag)
            list[{{ $key }}] = "{{ $tag }}";
        @endforeach
        new Tagify(input1, {
            whitelist: list,
            dropdown: {
                classname: "", // <- custom classname for this dropdown, so it could be targeted
                enabled: 0,             // <- show suggestions on focus
                closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
            }
        });


        var myDropzone1 = new Dropzone("#video", {
            url: "{{ route('admin.articles.upload.videos') }}",
            paramName: "video",
            acceptedFiles: 'video/*',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            accept: function(file, done) {
                done();
            },
            success: function (file, res) {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    toastr.success('آپلود با موفقیت انجام شد');
                }

                let html = `<div class="dz-message needsclick">
                        <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
                        <div class="ms-4">
                            <h3 class="fs-5 fw-bolder text-gray-900 mb-1">فایل ها را اینجا رها کنید یا برای آپلود کلیک کنید.</h3>
                            <span class="fs-7 fw-bold text-gray-400">حداکثر 10 فایل را آپلود کنید</span>
                        </div>
                    </div>
                `;
                $('#video').html(html);
                localStorage.setItem('video-urls', JSON.stringify(res.urls));

                $('#video-list').html('');
                res.urls.forEach(function (url) {
                    let html =
                    `<div class="col-md-4 mb-3">
                        <div class="article-media">
                            <button type="button" onclick="deletePermanentlyVideo(${url['index']})" title="حذف ویدیو" class="btn btn-danger btn-sm">حذف</button>
                            <video controls>
                                <source src="${siteUrl}/${url['url']}" type="video/mp4">
                            </video>
                        </div>
                    </div>`;

                    $('#video-list').append(html);
                });
            },
            error: function(res, err) {
                toastr.error(err.message);
            }
        });

        var siteUrl = "{{ url('/') }}";

        try {

            var myDropzone2 = new Dropzone("#gallery", {
                url: "{{ route('admin.articles.upload.photos') }}",
                paramName: "photo",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                acceptedFiles: 'image/*',
                accept: function(file, done) {
                    done();
                },
                success: function (file, res) {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                        toastr.success('آپلود با موفقیت انجام شد');
                    }

                    let html = `<div class="dz-message needsclick">
                            <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
                            <div class="ms-4">
                                <h3 class="fs-5 fw-bolder text-gray-900 mb-1">فایل ها را اینجا رها کنید یا برای آپلود کلیک کنید.</h3>
                                <span class="fs-7 fw-bold text-gray-400">حداکثر 10 فایل را آپلود کنید</span>
                            </div>
                        </div>
                    `;
                    $('#gallery').html(html);

                    localStorage.setItem('gallery-urls', JSON.stringify(res.urls));

                    $('#gallery-list').html('');
                    res.urls.forEach(function (url) {
                        let html =
                        `<div class="col-md-3 mb-3">
                            <div class="article-media">
                                <button type="button" onclick="deletePermanentlyGallery('${url['index']}')" title="حذف تصویر" class="btn btn-danger btn-sm">حذف</button>
                                <a href="${siteUrl}/${url['url']}" target="_blank">
                                    <img src="${siteUrl}/${url['url']}" alt="${siteUrl}/${url['url']}">
                                </a>
                            </div>
                        </div>`;

                        $('#gallery-list').append(html);
                    });
                },error: function(res, err) {
                    toastr.error(err.message);
                }
            });
        } catch (err) {
            console.log( err.message);
        }

        function loadPermanentlyGallery() {
            @if (session()->has('success'))
                localStorage.removeItem('gallery-urls');
            @endif

            let urls = localStorage.getItem('gallery-urls');

            if (urls) {
                urls = JSON.parse(urls);

                $('#gallery-list').html('');

                urls.forEach(function (url) {
                    let html =  `<div class="col-md-3 mb-3">
                                    <div class="article-media">
                                        <button type="button" onclick="deletePermanentlyGallery('${url['index']}')" title="حذف تصویر" class="btn btn-danger btn-sm">حذف</button>
                                        <a href="${siteUrl}/${url['url']}" target="_blank">
                                            <img src="${siteUrl}/${url['url']}" alt="${siteUrl}/${url['url']}">
                                        </a>
                                    </div>
                                </div>`;

                    $('#gallery-list').append(html);
                });
            }
        }

        loadPermanentlyGallery();

        function loadPermanentlyVideo() {
            @if (session()->has('success'))
                localStorage.removeItem('video-urls');
            @endif

            let urls = localStorage.getItem('video-urls');
            console.log(urls);
            if (urls) {
                urls = JSON.parse(urls);

                $('#video-list').html('');

                urls.forEach(function (url) {
                    let html =  `<div class="col-md-4 mb-3">
                                    <div class="article-media">
                                        <button type="button" onclick="deletePermanentlyVideo(${url['index']})" title="حذف تصویر" class="btn btn-danger btn-sm">حذف</button>
                                        <video controls>
                                            <source src="${siteUrl}/${url['url']}" type="video/mp4">
                                        </video>
                                    </div>
                                </div>`;

                    $('#video-list').append(html);
                });
            }
        }

        loadPermanentlyVideo();

        function deletePermanentlyGallery(index) {
            if (index) {
                index = index.split(',');
                if (index.length == 2) {
                    index[0] = Number(index[0]);
                    index[1] = Number(index[1]);
                    Swal.fire({
                        title: "آیا مایل به حذف آیتم انتخاب شده هستید؟",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'بله',
                        cancelButtonText: 'خیر',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "POST",
                                url: "{{ route('admin.articles.delete.permanently.gallery') }}",
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}',
                                    index: index,
                                },
                                dataType: "json",
                                success (response) {

                                    localStorage.setItem('gallery-urls', JSON.stringify(response.urls));

                                    toastr.success('آیتم انتخاب شده با موفقیت حذف شد!');

                                    loadPermanentlyGallery();
                                },
                                error (err) {
                                    toastr.error('خطا در حذف آیتم، لطفا مجدد تلاش نمایید');
                                }
                            });
                        }
                    });
                    console.log(index);
                }
            }
        }
        function deletePermanentlyVideo(index) {
            Swal.fire({
                title: "آیا مایل به حذف آیتم انتخاب شده هستید؟",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'بله',
                cancelButtonText: 'خیر',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.articles.delete.permanently.video') }}",
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}',
                            index: index,
                        },
                        dataType: "json",
                        success (response) {

                            localStorage.setItem('video-urls', JSON.stringify(response.urls));

                            toastr.success('آیتم انتخاب شده با موفقیت حذف شد!');

                            loadPermanentlyVideo();
                        },
                        error (err) {
                            toastr.error('خطا در حذف آیتم، لطفا مجدد تلاش نمایید');
                        }
                    });
                }
            });
        }

    </script>
@endpush

