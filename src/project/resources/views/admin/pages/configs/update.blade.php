@extends('admin.theme.master')
@section('admin-title')
    تنظیمات
@stop

@section('admin-toolbar')
@include('admin.theme.elements.toolbar', [
    'access' => 'admin.configs.update',
    'type' => 'noreturn',
])
@endsection
@section('admin-content')
@include('admin.theme.errors')
    <div class="card card-xl-stretch shadow-sm mb-5 mb-xl-8" style="direction: rtl;text-align:right">
        <div class="card-body py-6">
            <ul class="nav nav-tabs nav-line-tabs mb-10 fs-6">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1"><i class="fa fa-cog" aria-hidden="true"></i> تنظیم اصلی</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2"><i class="fa-solid fa-envelope"></i> تنظیمات پیامک</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_3"><i class="fas fa-yoast"></i> تنظیمات سئو</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_4"><i class="fas fa-message"></i> تنظیمات وب سرویس ها</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_5"><i class="fas fa-message"></i > پیشنهادها</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_6"><i class="fa-solid fa-handshake"></i> تنظیمات آیکون های اجتماعی</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_7"><i class="fa-solid fa-image"></i> تنظیمات تصاویر</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_8"><i class="fas fa-gavel"></i> قوانین و ضوابط سایت</a>
                </li>
            </ul>

            <form action="{{ route('admin.configs.main.update') }}" enctype="multipart/form-data" method="post" id="create_form">
                 @csrf
                 @method('patch')
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                        <div class="row mb-10">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-6"><label class="form-check-label" for="general_offline">آفلاین</label></div>
                                    <div class="col-6">
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" name="general_offline" @checked($configs['general_offline']) type="checkbox" value="1" id="general_offline"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-6"><label class="form-check-label" for="general_access_register">ثبت نام عمومی</label></div>
                                    <div class="col-6">
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" name="general_access_register"  @checked($configs['general_access_register']) type="checkbox" value="1" id="general_access_register"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-6">
                                <label class="form-check-label" for="general_offline_text">متن آفلاین</label>
                                <textarea class="form-control resize-none" rows="5" id="general_offline_text" name="general_offline_text">{{ $configs['general_offline_text'] }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-check-label" for="general_access_ip">دسترسی آی پی ها</label>
                                <div style="position: relative;">
                                    <button class="btn btn-primary btn-sm btn-hover-scale" onclick="copyMyIp()" style="position: absolute;left:0;top:-100%;" type="button">کپی آی پی فعلی</button>
                                    <input class="form-control form-control-solid" dir="ltr" id="general_access_ip" value="{{ $configs['general_access_ip'] }}" name="general_access_ip"/>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-4">
                                <label class="form-label" for="general_default_logo">تغییر تصویر لوگو</label><br>
                                <div class="image-input image-input-empty image-input-outline mb-3"
                                    data-kt-image-input="true">
                                    <div class="image-input-wrapper w-150px h-150px"
                                            style="background-position: center;background-size: 100% 100%;background-image: url('{{url($configs['general_default_logo'])}}')">
                                    </div>
                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                        title="انتخاب تصویر">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" id="general_default_logo" name="general_default_logo" accept=".png, .jpg, .jpeg"/>
                                    </label>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                        title="حذف تصویر">
                                        <i class="fas fa-remove fs-2"></i>
                                    </span>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                        title="حذف تصویر">
                                        <i class="fas fa-car fs-2"></i>
                                    </span>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="general_default_avatar">تغییر تصویر پروفایل</label><br>
                                <div class="image-input image-input-empty image-input-outline mb-3"
                                    data-kt-image-input="true">
                                    <div class="image-input-wrapper w-150px h-150px"
                                            style="background-position: center;background-size: 100% 100%;background-image: url('{{url($configs['general_default_avatar'])}}')">
                                    </div>
                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                        title="انتخاب تصویر">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" id="general_default_avatar" name="general_default_avatar" accept=".png, .jpg, .jpeg"/>
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
                            <div class="col-md-4">
                                <label class="form-label" for="general_default_avatar">تغییر تصویر فاوآیکون</label><br>
                                <div class="image-input image-input-empty image-input-outline mb-3"
                                    data-kt-image-input="true">
                                    <div class="image-input-wrapper w-150px h-150px"
                                            style="background-position: center;background-size: 100% 100%;background-image: url('{{url($configs['image_favicon'])}}')">
                                    </div>
                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                        title="انتخاب تصویر">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" id="image_favicon" name="image_favicon" accept=".png, .jpg, .jpeg"/>
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
                        </div>
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                        <div class="row mb-10">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-6"><label class="form-check-label" for="sms_active">وضعیت</label></div>
                                    <div class="col-6">
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" onchange="showHideConfigControl(this, 'box-sms-active')" name="sms_active" @checked($configs['sms_active']) type="checkbox" value="1" id="sms_active"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                        <div id="box-sms-active" class="{{ $configs['sms_active'] ? '' : 'd-none' }}">
                            <div class="row mb-10">
                                <div class="col-md-12">
                                    <label class="form-check-label" for="sms_manager_mobile">کاربران</label>
                                    <select multiple  data-control="select2" class="form-select form-select-solid" id="sms_manager_mobile"  name="sms_manager_mobile[]">
                                        @foreach ($users as $key => $value)
                                            <option value="{{ $key }}" {{ in_array($key, $configs['sms_manager_mobile']) ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-10">
                                <div class="col-md-6">
                                    <label class="form-check-label" for="sms_username">نام کاربری</label>
                                    <input class="form-control" dir="ltr" autocomplete="off" name="sms_username" type="text" value="{{ $configs['sms_username'] }}"/>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-check-label" for="sms_password">رمز عبور</label>
                                    <input class="form-control" dir="ltr" autocomplete="off" name="sms_password" type="text" value="{{ $configs['sms_password'] }}"/>
                                </div>
                            </div>
                            <div class="row mb-10">
                                <div class="col-md-6">
                                    <label class="form-check-label" for="sms_number">شماره پنل</label>
                                    <input class="form-control" dir="ltr" autocomplete="off" name="sms_number" type="text" value="{{ $configs['sms_number'] }}"/>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-check-label" for="sms_url">لینک</label>
                                    <input class="form-control" autocomplete="off" dir="ltr" placeholder="" name="sms_url" type="text" value="{{ $configs['sms_url'] }}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
                        <div class="row mb-10">
                            <div class="col-md-12 mb-7">
                                <label class="form-check-label" for="seo_title">عنوان متا</label>
                                <input class="form-control" autocomplete="off"
                                 name="seo_title" type="text" value="{{ $configs['seo_title'] }}"/>
                            </div>
                            <div class="col-md-12">
                                <label class="form-check-label" for="seo_google_webmaster">کد گوگل وب مستر</label>
                                <textarea class="form-control resize-none" autocomplete="off" rows="5" name="seo_google_webmaster">{{ $configs['seo_google_webmaster'] }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_4" role="tabpanel">
                        <div class="row mb-10">
                            <div class="col-12 mb-5 fs-14 border p-5">
                                <p>شما می توانید به وب سایت زیر مراجعه کرده و کلید های مورد نظر خود را دریافت و در بخش زیر وارد نمایید</p>
                                <a target="_blank" href="https://www.google.com/recaptcha/admin" class="btn btn-primary btn-sm">ریکپچای وب سایت گوگل</a>
                            </div>
                            <div class="col-md-6">
                                <label class="form-check-label" for="api_recaptcha_secret">Secret Key</label>
                                <input class="form-control" dir="ltr" name="api_recaptcha_secret" type="text" value="{{ $configs['api_recaptcha_secret'] }}"/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-check-label" for="api_recaptcha_client">Client Key</label>
                                <input class="form-control" dir="ltr" name="api_recaptcha_client" type="text" value="{{ $configs['api_recaptcha_client'] }}"/>
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-12 mb-5 fs-14 border p-5">
                                <p>شما می توانید برای راه اندازی نرم افزار چت به سایت زیر مراجعه کرده و کد مورد نظر خود را دریافت و در بخش زیر وارد نمایید</p>
                                <a target="_blank" href="https://crisp.chat" class="btn btn-primary btn-sm"> وب سایت کریسپ</a>
                            </div>
                            <div class="col-md-12">
                                <label class="form-check-label" for="api_crisp_id">Crisp Website Id</label>
                                <input class="form-control" dir="ltr" name="api_crisp_id" type="text" value="{{ $configs['api_crisp_id'] }}"/>
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-12 mb-5 fs-14 border p-5">
                                <p>شما می توانید برای راه اندازی نرم افزار چت فارسی به وب سایت زیر مراجعه کرده و کد مورد نظر خود را دریافت و در بخش زیر وارد نمایید</p>
                                <a target="_blank" href="https://raychat.io" class="btn btn-primary btn-sm">وب سایت رایچت</a>
                            </div>
                            <div class="col-md-12">
                                <label class="form-check-label" for="api_raychat">Raychat Website</label>
                                <input class="form-control" dir="ltr" name="api_raychat" type="text" value="{{ $configs['api_raychat'] }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_5" role="tabpanel">
                        <div class="row mb-10">
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-6"><label class="form-check-label" for="promotion_active">تنظیم پیشنهاد</label></div>
                                    <div class="col-6">
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" name="promotion_active" @checked($configs['promotion_active']) type="checkbox" value="1" id="promotion_active"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-6"><label class="form-check-label" for="promotion_loop">تکرار با هر بارگذاری صفحه</label></div>
                                    <div class="col-6">
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" name="promotion_loop" @checked($configs['promotion_loop']) type="checkbox" value="1" id="promotion_loop"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-3"><label class="form-check-label" for="promotion_type">نوع</label></div>
                                    <div class="col-9">
                                        <select name="promotion_type" id="promotion_type" class="form-select">
                                            <option value="1">پاپ آپ</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-6">
                                <label class="form-check-label" for="promotion_expire">تاریخ پایان پیشنهاد</label>
                                <input class="form-control" data-jdp dir="ltr" name="promotion_expire" value="{{ $configs['promotion_expire'] }}" type="text" id="promotion_expire"/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-check-label" for="promotion_link">لینک</label>
                                <input class="form-control" autocomplete="off" dir="ltr" name="promotion_link" type="text" value="{{ $configs['promotion_link'] }}" id="promotion_link"/>
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-6">
                                <label class="form-check-label" for="promotion_image">تغییر تصویر پیشنهاد</label><br>
                                <div class="image-input image-input-empty image-input-outline mb-3"
                                    data-kt-image-input="true">
                                    <div class="image-input-wrapper w-150px h-150px"
                                            style="background-position: center;background-size: 100% 100%;background-image: url('{{url($configs['promotion_image'], '')}}')">
                                    </div>
                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                        title="انتخاب تصویر">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" id="promotion_image" name="promotion_image" accept=".png, .jpg, .jpeg"/>
                                    </label>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                        title="حذف تصویر">
                                        <i class="fas fa-remove fs-2"></i>
                                    </span>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                        title="حذف تصویر">
                                        <i class="fas fa-remove fs-2"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_6" role="tabpanel">
                        <div class="row mb-10">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-6"><label class="form-check-label" for="social_share">وضعیت</label></div>
                                    <div class="col-6">
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" name="social_share" @checked($configs['social_share']) onchange="showHideConfigControl(this, 'socials')" type="checkbox" value="1" id="social_share"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-10 {{ $configs['social_share'] ? '' : 'd-none' }}" id="socials">
                            <div class="form-group">
                                <div data-repeater-list="socials">
                                    @forelse ($configs['socials'] as $value)
                                        <div data-repeater-item class="mb-7">
                                            <div class="form-group row">
                                                <div class="col-md-5">
                                                    <input type="text" name="socialName" autocomplete="off" placeholder="مثال : فیسبوک" value="{{ $value['name'] }}" class="form-control mb-2 mb-md-0"/>
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" name="socialUrl" autocomplete="off" dir="ltr" placeholder="مثال : https://facebook.com" value="{{ $value['url'] }}" class="form-control mb-2 mb-md-0"/>
                                                </div>
                                                <div class="col-md-2">
                                                    <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger">
                                                        <i class="la la-trash-o"></i>حذف
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div data-repeater-item class="mb-7">
                                            <div class="form-group row">
                                                <div class="col-md-5">
                                                    <input type="text" name="socialName" class="form-control mb-2 mb-md-0"/>
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" name="socialUrl" class="form-control mb-2 mb-md-0"/>
                                                </div>
                                                <div class="col-md-2">
                                                    <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger">
                                                        <i class="la la-trash-o"></i>حذف
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            <div class="form-group mt-5">
                                <a href="javascript:;" data-repeater-create class="btn btn-light-primary btn-sm">
                                    <i class="la la-plus"></i>افزودن
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_7" role="tabpanel">
                        <div class="row mb-10">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-6"><label class="form-check-label" for="image_optimize_active">بهینه سازی تصویر</label></div>
                                    <div class="col-6">
                                        <div class="form-check form-switch form-check-custom form-check-solid" >
                                            <input class="form-check-input" name="image_optimize_active" onchange="showHideConfigControl(this, 'optimize-div')" @checked($configs['image_optimize_active']) type="checkbox" value="1" id="image_optimize_active"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 {{ $configs['image_optimize_active'] ? '' : 'd-none' }}" id="optimize-div">
                                <label for="image_optimize_value" class="form-label">مقدار کوآلیتی</label>
                                <input class="form-control" name="image_optimize_value" type="text" value="{{ $configs['image_optimize_value'] }}" id="image_optimize_value"/>
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-6"><label class="form-check-label" for="image_watermark_active">واترمارک</label></div>
                                    <div class="col-6">
                                        <div class="form-check form-switch form-check-custom form-check-solid" >
                                            <input class="form-check-input" name="image_watermark_active" onchange="showHideConfigControl(this, 'watermark-div')" @checked($configs['image_watermark_active']) type="checkbox" value="1" id="image_watermark_active"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-10 {{ $configs['image_watermark_active'] ? '' : 'd-none' }}" id="watermark-div">
                            <div class="col-6 mb-10">
                                <label for="image_watermark_file" class="form-label">تصویر واترمارک</label><br>
                                <div class="image-input image-input-empty image-input-outline mb-3"
                                    data-kt-image-input="true">
                                    <div class="image-input-wrapper w-150px h-150px"
                                            style="background-position: center;background-size: 100% 100%;background-image: url('{{url($configs['image_watermark_file'])}}')">
                                    </div>
                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                        title="انتخاب تصویر">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" id="image_watermark_file" name="image_watermark_file" accept=".png, .jpg, .jpeg"/>
                                    </label>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                        title="حذف تصویر">
                                        <i class="fas fa-remove fs-2"></i>
                                    </span>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                        data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                        title="حذف تصویر">
                                        <i class="fas fa-remove fs-2"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-6 mb-10">
                                <label for="image_watermark_opacity" class="form-label">مقدار شفافیت</label>
                                <input class="form-control" name="image_watermark_opacity" type="text" value="{{ $configs['image_watermark_opacity'] }}" id="image_watermark_opacity"/>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="image_watermark_percent" class="form-label">درصد</label>
                                <input class="form-control" name="image_watermark_percent" type="text" value="{{ $configs['image_watermark_percent'] }}" id="image_watermark_percent"/>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="image_watermark_position" class="form-label">موقعیت</label>
                                    <select name="image_watermark_position" class="form-select">
                                        <option value="">انتخاب نمایید</option>
                                        <option value="left-top" {{ $configs['image_watermark_position'] == 'left-top' ? 'selected' : '' }}>left-top</option>
                                        <option value="left-center" {{ $configs['image_watermark_position'] == 'left-center' ? 'selected' : '' }}>left-center</option>
                                        <option value="left-bottom" {{ $configs['image_watermark_position'] == 'left-bottom' ? 'selected' : '' }}>left-bottom</option>
                                        <option value="top-center" {{ $configs['image_watermark_position'] == 'top-center' ? 'selected' : '' }}>top-center</option>
                                        <option value="center-center" {{ $configs['image_watermark_position'] == 'center-center' ? 'selected' : '' }}>center-center</option>
                                        <option value="bottom-center" {{ $configs['image_watermark_position'] == 'bottom-center' ? 'selected' : '' }}>bottom-center</option>
                                        <option value="right-top" {{ $configs['image_watermark_position'] == 'right-top' ? 'selected' : '' }}>right-top</option>
                                        <option value="right-center" {{ $configs['image_watermark_position'] == 'right-center' ? 'selected' : '' }}>right-center</option>
                                        <option value="right-bottom" {{ $configs['image_watermark_position'] == 'right-bottom' ? 'selected' : '' }}>right-bottom</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-6"><label class="form-check-label" for="image_resize_active">تغییر ابعاد تصویر</label></div>
                                    <div class="col-6">
                                        <div class="form-check form-switch form-check-custom form-check-solid" >
                                            <input class="form-check-input" name="image_resize_active" onchange="showHideConfigControl(this, 'image-resize-div')" @checked($configs['image_resize_active']) type="checkbox" value="1" id="image_resize_active"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 {{ $configs['image_resize_active'] ? '' : 'd-none' }}" id="image-resize-div">
                                <label for="image_resize_value" class="form-label">انتخاب اندازه</label>
                                <input class="form-control form-control-solid" placeholder="مثال: 300-200" id="image_resize_value" value="{{ $configs['image_resize_value'] }}" name="image_resize_value"/>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_8" role="tabpanel">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">قوانین و ضوابط سایت:</label>
                                <textarea name="toc" class="form-control resize-none" rows="10">{{ $configs['toc'] }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
@push('admin-css')
<link type="text/css" rel="stylesheet" href="{{ url('cdn/theme/admin/css/jalalidatepicker.min.css') }}" />
    <style>
        .modal-body {
            text-align: right !important;
        }
        label {
            font-weight: bold;
            margin-bottom: 8px;
        }
    </style>
@endpush
@push('admin-js')
    <script src="{{ url('cdn/theme/admin/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ url('cdn/theme/admin/js/jalalidatepicker.min.js')}}"></script>
    <script>
        jalaliDatepicker.startWatch({
            time:true,
            initDate:null,
            initTime:null,
            separatorChars:{
                date:"-",
                time:":",
                between:" "
            }
        });

        function showHideConfigControl(el, id) {
            if (el.checked) {
                $('#' + id).removeClass('d-none');
            } else {
                $('#' + id).addClass('d-none');
            }
        }

        var input = document.querySelector('#image_resize_value'),
        tagify = new Tagify(input, {
            placeholder: "چیزی بنویسید",
            enforceWhitelist: false
        });

        var input = document.querySelector('#general_access_ip'),
        tagify = new Tagify(input, {
            placeholder: "چیزی بنویسید",
            enforceWhitelist: false
        });

        $('#socials').repeater({
            initEmpty: false,

            defaultValues: {
                'text-input': 'foo'
            },

            show: function () {
                $(this).slideDown();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });

        function copyMyIp() {
            let ip = '{{ request()->ip() }}'
            let $temp = $("<input>");
            $("body").append($temp);
            $temp.val(ip).select();
            document.execCommand("copy");
            $temp.remove();
        }
    </script>
@endpush

