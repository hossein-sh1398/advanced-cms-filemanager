@extends('admin.theme.master')

@push('admin-css')
    <style>
        #timer {
            margin-left: 10px;
        }
    </style>
@endpush

@section('admin-toolbar')
    @include('admin.theme.elements.toolbar', [
        'route' => route('admin.index'),
        'access' => 'admin.index',
        'type' => true,
    ])
@stop
@section('admin-content')
    <!--begin::Basic info-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
            aria-expanded="true">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">پروفایل کاربری</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details" class="collapse show">
            @include('admin.theme.errors')
            <!--begin::Form-->
            <form id="create_form" method="POST" action="{{ route('admin.profile.update') }}" class="form" enctype="multipart/form-data">
                @csrf
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">تصویر پروفایل</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Image input-->
                            <div class="image-input image-input-outline" data-kt-image-input="true"
                                 style="background-image: url({{ url($user->avatar()) }})">
                                <!--begin::Preview existing avatar-->
                                <div class="image-input-wrapper w-125px h-125px"
                                     style="background-position: center;background-size: 100% 100%;background-image: url({{ url($user->avatar()) }})"></div>
                                <!--end::Preview existing avatar-->

                                <!--begin::Label-->
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                       data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                       title="Change avatar">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <!--begin::Inputs-->
                                    <input type="file" name="avatar" accept=".png, .jpg, .jpeg"/>
                                    <!--end::Inputs-->
                                </label>
                                <!--end::Label-->
                                <!--begin::Cancel-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                      data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                      title="Cancel avatar">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                <!--end::Cancel-->
                                <!--begin::Remove-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                      data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                      title="Remove avatar">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                <!--end::Remove-->
                            </div>
                            <!--end::Image input-->
                            <!--begin::Hint-->
                            <div class="form-text">تصاویر قابل استفاده : png, jpg, jpeg</div>
                            <!--end::Hint-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label required fw-semibold fs-6" for="name">نام</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Row-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input type="text" name="name" id="name"
                                           class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                           autocomplete="off"
                                           placeholder="نام" value="{{ old('name', $user->name ?? '') }}"/>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6" for="mobile">
                            <span class="required">شماره همراه</span>
                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                               title="Phone number must be active"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input type="tel" name="mobile" id="mobile"
                                   class="form-control form-control-lg form-control-solid"
                                   autocomplete="off"
                                   placeholder="شماره همراه"
                                   value="{{ old('mobile', $user->mobile ?? '') }}"/>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6 required" for="email">ایمیل</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <input  type="text"
                                    name="email"
                                    id="email"
                                    class="form-control form-control-lg form-control-solid"
                                    autocomplete="off"
                                    placeholder="پست الکترونیکی"
                                    value="{{ old('email', $user->email ?? '') }}"/>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6" for="password">رمز عبور</label>
                        <!--end::Label-->
                        <!--begin::Col-->

                        <div class="col-lg-8">
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <input  type="password"
                                            name="password"
                                            id="password"
                                            class="form-control form-control-lg form-control-solid"
                                            autocomplete="off"
                                            placeholder="رمز عبور"/>
                                </div>
                                <div class="col-lg-6 fv-row">
                                    <input  type="password"
                                            name="re_password"
                                            id="re_password"
                                            class="form-control form-control-lg form-control-solid"
                                            autocomplete="off"
                                            placeholder="تایید رمز عبور"/>
                                </div>
                                <!--end::Col-->
                            </div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6" for="twoStepMethodStatus">تایید دو مرحله
                            ای</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        <div class="col-lg-8 d-flex align-items-center">
                            <div class="form-check form-switch mb-10">
                                <input type="hidden" id="twoStepMethodType" name="twoStepMethodType">
                                <input class="form-check-input" name="twoStepMethodStatus" value="1" type="checkbox" @checked($user->two_step_status && $user->two_step_type) role="switch" id="twoStepMethodStatus"/>
                                <label class="form-check-label"></label>
                            </div>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <div class="row mb-6" id="twoStepMethod" style="display: {{ ($user->two_step_status && $user->two_step_type) ? '' : 'none' }}">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">انتخاب نوع فعال سازی</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        <div class="col-lg-8 fv-row">
                            <div class="notice d-flex bg-light-success rounded border-success border border-dashed p-6">
                                <!--begin::Icon-->
                                <!--begin::Svg Icon | path: icons/duotune/general/gen048.svg-->
                                <span class="svg-icon svg-icon-2tx svg-icon-success me-4">
							    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
							    	<path opacity="0.3"
                                          d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z"
                                          fill="currentColor"/>
							    	<path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z"
                                          fill="currentColor"/>
							    </svg>
                            </span>
                                <!--end::Svg Icon-->
                                <!--end::Icon-->
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                                    <!--begin::Content-->
                                    <div class="mb-3 mb-md-0 fw-semibold">
                                        <div class="fs-6 text-gray-700 pe-7">احراز هویت دو مرحله ای یک لایه امنیتی اضافی
                                            به حساب شما اضافه می کند. برای ورود به سیستم، علاوه بر این باید یک کد 6 رقمی
                                            ارائه دهید
                                        </div>
                                    </div>
                                    <!--end::Content-->
                                    <!--begin::Action-->
                                    <a href="#" class="btn btn-success btn-sm px-6 align-self-center text-nowrap"
                                       data-bs-toggle="modal" data-bs-target="#kt_modal_two_factor_authentication">فعال
                                        سازی</a>
                                    <!--end::Action-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                        </div>
                        <!--begin::Label-->
                    </div>

                    <!--end::Input group-->
                </div>
                <!--end::Card body-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
    <!--end::Notifications-->
    <!--begin::Modal - Two-factor authentication-->
    <div class="modal fade" id="kt_modal_two_factor_authentication" tabindex="-1" aria-hidden="true">
        <!--begin::Modal header-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header flex-stack">
                    <!--begin::Title-->
                    <h2>انتخاب نوع احراز هویت دو مرحله ای</h2>
                    <!--end::Title-->
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
									<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                          transform="rotate(-45 6 17.3137)" fill="currentColor"/>
									<rect x="7.41422" y="6" width="16" height="2" rx="1"
                                          transform="rotate(45 7.41422 6)" fill="currentColor"/>
								</svg>
							</span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--begin::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y pt-10 pb-15 px-lg-17">
                    <!--begin::Options-->
                    <div data-kt-element="options">
                        <!--begin::Notice-->
                        <p class="text-muted fs-5 fw-semibold mb-10">
                            در این مرحله شما میتوانید با انتخاب یکی از گزینه های زیر احراز هویت دو مرحله ای خود را انجام
                            دهید تا امنیت اکانت خود را بالا ببرید.
                        </p>
                        <!--end::Notice-->
                        <!--begin::Wrapper-->
                        <div class="pb-10">
                            <!--begin::Option-->
                            <input type="radio" class="btn-check" name="auth_option" value="3"
                                   id="kt_modal_two_factor_authentication_option_1"
                                   @checked($user->twoStepType(\App\Enums\TwoStepType::Google))/>
                            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center mb-5"
                                   for="kt_modal_two_factor_authentication_option_1">
                                <!--begin::Svg Icon | path: icons/duotune/coding/cod001.svg-->
                                <span class="svg-icon svg-icon-4x me-4">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
											<path opacity="0.3"
                                                  d="M22.1 11.5V12.6C22.1 13.2 21.7 13.6 21.2 13.7L19.9 13.9C19.7 14.7 19.4 15.5 18.9 16.2L19.7 17.2999C20 17.6999 20 18.3999 19.6 18.7999L18.8 19.6C18.4 20 17.8 20 17.3 19.7L16.2 18.9C15.5 19.3 14.7 19.7 13.9 19.9L13.7 21.2C13.6 21.7 13.1 22.1 12.6 22.1H11.5C10.9 22.1 10.5 21.7 10.4 21.2L10.2 19.9C9.4 19.7 8.6 19.4 7.9 18.9L6.8 19.7C6.4 20 5.7 20 5.3 19.6L4.5 18.7999C4.1 18.3999 4.1 17.7999 4.4 17.2999L5.2 16.2C4.8 15.5 4.4 14.7 4.2 13.9L2.9 13.7C2.4 13.6 2 13.1 2 12.6V11.5C2 10.9 2.4 10.5 2.9 10.4L4.2 10.2C4.4 9.39995 4.7 8.60002 5.2 7.90002L4.4 6.79993C4.1 6.39993 4.1 5.69993 4.5 5.29993L5.3 4.5C5.7 4.1 6.3 4.10002 6.8 4.40002L7.9 5.19995C8.6 4.79995 9.4 4.39995 10.2 4.19995L10.4 2.90002C10.5 2.40002 11 2 11.5 2H12.6C13.2 2 13.6 2.40002 13.7 2.90002L13.9 4.19995C14.7 4.39995 15.5 4.69995 16.2 5.19995L17.3 4.40002C17.7 4.10002 18.4 4.1 18.8 4.5L19.6 5.29993C20 5.69993 20 6.29993 19.7 6.79993L18.9 7.90002C19.3 8.60002 19.7 9.39995 19.9 10.2L21.2 10.4C21.7 10.5 22.1 11 22.1 11.5ZM12.1 8.59998C10.2 8.59998 8.6 10.2 8.6 12.1C8.6 14 10.2 15.6 12.1 15.6C14 15.6 15.6 14 15.6 12.1C15.6 10.2 14 8.59998 12.1 8.59998Z"
                                                  fill="currentColor"/>
											<path d="M17.1 12.1C17.1 14.9 14.9 17.1 12.1 17.1C9.30001 17.1 7.10001 14.9 7.10001 12.1C7.10001 9.29998 9.30001 7.09998 12.1 7.09998C14.9 7.09998 17.1 9.29998 17.1 12.1ZM12.1 10.1C11 10.1 10.1 11 10.1 12.1C10.1 13.2 11 14.1 12.1 14.1C13.2 14.1 14.1 13.2 14.1 12.1C14.1 11 13.2 10.1 12.1 10.1Z"
                                                  fill="currentColor"/>
										</svg>
									</span>
                                <!--end::Svg Icon-->
                                <span class="d-block fw-semibold text-start">
										<span class="text-dark fw-bold d-block fs-3">سرویس Google Authenticator</span>
                                    <span class="text-muted fw-semibold fs-6">شما میتوانید با برنامه Google Authenticator احراز هویت دو مرحله ای خود را انجام دهید</span>
									</span>
                            </label>
                            <!--end::Option-->
                            <!--begin::Option-->
                            @if($smsActive)
                                <input type="radio" class="btn-check" name="auth_option" value="2"
                                        @checked($user->twoStepType(\App\Enums\TwoStepType::Mobile))
                                        id="kt_modal_two_factor_authentication_option_2"/>
                                <label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center mb-5"
                                    for="kt_modal_two_factor_authentication_option_2">
                                    <!--begin::Svg Icon | path: icons/duotune/communication/com003.svg-->
                                    <span class="svg-icon svg-icon-4x me-4">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.3"
                                                    d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z"
                                                    fill="currentColor"/>
                                                <path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z"
                                                    fill="currentColor"/>
                                            </svg>
                                        </span>
                                    <!--end::Svg Icon-->
                                    <span class="d-block fw-semibold text-start">
                                            <span class="text-dark fw-bold d-block fs-3">پیامک</span>
                                        <span class="text-muted fw-semibold fs-6">شما میتوانید پیامک خود را برای احراز هویت دو مرحله ای انتخاب نمایید</span>
                                    </span>
                                </label>
                            @endif
                            <!--end::Option-->
                            <!--begin::Option-->
                            <input type="radio" class="btn-check" name="auth_option" value="1"
                                @checked($user->twoStepType(\App\Enums\TwoStepType::Email))
                                id="kt_modal_two_factor_authentication_option_3"/>
                            <label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center"
                                for="kt_modal_two_factor_authentication_option_3">
                                <span class="svg-icon svg-icon-4x me-4">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3"
                                            d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z"
                                            fill="currentColor"/>
                                        <path d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z"
                                            fill="currentColor"/>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                <!--end::Svg Icon-->
                                <span class="d-block fw-semibold text-start">
                                    <span class="text-dark fw-bold d-block fs-3">ایمیل</span>
                                    <span class="text-muted fw-semibold fs-6">شما میتوانید ایمیل خود را برای احراز هویت دو مرحله ای انتخاب نمایید</span>
                                </span>
                            </label>
                            <!--end::Option-->
                        </div>
                        <!--end::Options-->
                        <!--begin::Action-->
                        <button class="btn btn-primary w-100" onclick="activeTwoSetpType()" data-kt-element="options-select">ادامه</button>
                        <!--end::Action-->
                    </div>
                    <!--end::Options-->
                    <!--begin::Apps-->
                    <div class="d-none" data-kt-element="apps">
                        <!--begin::Heading-->
                        <h3 class="text-dark fw-bold mb-7 {{ $user->google2fa_secret && $user->twoStepType(3) ? 'd-none' : '' }}">برنامه احراز هویت دو مرحله ای</h3>
                        <!--end::Heading-->
                        <!--begin::Description-->
                        <div class="text-gray-500 fw-semibold fs-6 mb-10 {{ $user->google2fa_secret && $user->twoStepType(3) ? 'd-none' : '' }}">
                            {{-- <a href="https://support.google.com/accounts/answer/1066447?hl=en" target="_blank">Google
                                Authenticator</a>
                            <a href="https://www.microsoft.com/en-us/account/authenticator" target="_blank">Microsoft
                                Authenticator</a>,
                            <a href="https://authy.com/download/" target="_blank">Authy</a>, or
                            <a href="https://support.1password.com/one-time-passwords/" target="_blank">1Password</a>,
                            scan the QR code. It will generate a 6 digit code for you to enter below. --}}
                            <!--begin::QR code image-->
                            <div class="pt-5 text-center" id="google-qrcode" style="margin-right:auto;margin-letf:auto">
                            </div>
                            <!--end::QR code image--></div>
                        <!--end::Description-->
                        <!--begin::Notice-->
                        <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-10 p-6 {{ $user->google2fa_secret && $user->twoStepType(3) ? 'd-none' : '' }}">
                            <!--begin::Icon-->
                            <!--begin::Svg Icon | path: icons/duotune/general/gen044.svg-->
                            <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
										<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10"
                                              fill="currentColor"/>
										<rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)"
                                              fill="currentColor"/>
										<rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)"
                                              fill="currentColor"/>
									</svg>
								</span>
                            <!--end::Svg Icon-->
                            <!--end::Icon-->
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-stack flex-grow-1">
                                <!--begin::Content-->
                                <div class="fw-semibold">
                                    <div class="fs-6 text-gray-700">
                                        {{-- If you having trouble using the QR code, select
                                        manual entry on your app, and enter your username and the code: --}}
                                        <div class="fw-bold text-dark pt-2" id="secret-key"></div>
                                    </div>
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Notice-->
                        <!--begin::Form-->
                        <form data-kt-element="apps-form" class="form" action="#">
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row {{ $user->google2fa_secret && $user->twoStepType(3) ? 'd-none' : '' }}">
                                <input type="text" class="form-control form-control-lg form-control-solid"
                                       placeholder="کد تایید را اینجا وارد نمایید" name="form-google-code-verify"/>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Actions-->
                            <div class="d-flex flex-center">
                                <button type="reset" data-kt-element="apps-cancel" class="btn btn-light me-3 btn-sm">بازگشت
                                </button>
                                {{-- 2 is mobile --}}
                                <span class="badge badge-success badge-tow-step-type-status-mobile {{ $user->twoStepType(3) ? '' : 'd-none' }}">
                                    فعال می باشد
                                </span>
                                <button type="submit" data-kt-element="apps-submit" class="btn btn-primary btn-sm {{ $user->google2fa_secret && $user->twoStepType(3) ? 'd-none' : '' }}">
                                    <span class="indicator-label">ثبت</span>
                                    <span class="indicator-progress">لطفا کمی صبر کنید
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Options-->
                    <!--begin::SMS-->
                    <div class="d-none" data-kt-element="sms">
                        <!--begin::Heading-->
                        <h3 class="text-dark fw-bold fs-3 mb-5 {{ $user->mobile_verified_at ? 'd-none' : '' }}" >پیامک: تایید شماره موبایل</h3>
                        <!--end::Heading-->
                        <!--begin::Notice-->
                        <div class="btn btn-outline btn-outline-dashed align-items-center col-12 {{ $user->mobile_verified_at ? 'd-none' : '' }}">
                            <div class="text-muted fw-semibold mb-5">
                                لطفا بازدن دکمه ارسال کد تایید کد تایید شده به موبایل خود را وارد نمایید
                            </div>
                            <!--end::Heading-->
                            <!--begin::Notice-->
                            <div class="text-muted fw-semibold mb-5 d-none" id="timerCounterDown-2"></div>
                            <button class="btn btn-sm btn-success {{ $user->mobile_verified_at ? 'd-none' : '' }}" id="confirmTimerButton-2" type="button" onclick="confirmTimer(2);verifyMobileCode()">ارسال کد تایید
                            </button>
                        </div>
                        <!--end::Notice-->
                        <!--begin::Form-->
                        <form data-kt-element="sms-form" class="form" action="#">
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row {{ $user->mobile_verified_at ? 'd-none' : '' }}">
                                <input type="text" class="form-control form-control-lg form-control-solid"
                                    placeholder="کد تایید را وارد نمایید" autocomplete="off" name="mobile" id="form-verify-mobile-code"/>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Actions-->
                            <div class="d-flex flex-center">
                                <button type="reset" data-kt-element="sms-cancel" class="btn btn-light me-3">بازگشت</button>
                                <button type="submit" data-kt-element="sms-submit" class="btn btn-primary btn-sm {{ $user->mobile_verified_at ? 'd-none' : '' }}">
                                    <span class="indicator-label">تایید</span>
                                    <span class="indicator-progress">لطفا کمی صبر کنید
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                {{-- 2 is mobile --}}
                                <span class="badge badge-success badge-tow-step-type-status-mobile {{ $user->mobile_verified_at && $user->twoStepType(2) ? '' : 'd-none' }}">
                                    فعال می باشد
                                </span>
                                <button onclick="selectStepType(2)" type="button" class="btn btn-primary btn-sm {{ $user->mobile_verified_at && ($user->twoStepType(1) || $user->twoStepType(3)) ? '' : 'd-none' }}">
                                    <span class="indicator-label">فعال شود</span>
                                    <span class="indicator-progress">لطفا کمی صبر کنید
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::SMS-->
                    <!--begin::EMAIL-->
                    <div class="d-none" data-kt-element="email">
                        <!--begin::Heading-->
                        <div class="btn btn-outline btn-outline-dashed align-items-center col-12 {{ $user->email_verified_at ? 'd-none' : '' }}">
                            <div class="text-muted fw-semibold mb-5">
                                لطفا بازدن دکمه ارسال کد تایید کد تایید شده به ایمیل خود را وارد نمایید
                            </div>
                            <!--end::Heading-->
                            <!--begin::Notice-->
                            <div class="text-muted fw-semibold mb-5 d-none" id="timerCounterDown-1"></div>
                            <button class="btn btn-sm btn-success" id="confirmTimerButton-1" type="button"
                                    onclick="confirmTimer(1);verifyEmailCode()">ارسال کد تایید
                            </button>
                        </div>

                        <!--end::Notice-->
                        <!--begin::Form-->
                        <form data-kt-element="email-form" class="form mt-5">
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row {{ $user->email_verified_at ? 'd-none' : '' }}">
                                <input type="text" class="form-control form-control-lg form-control-solid"
                                    placeholder="کد تایید را وارد نمایید" id="form-verify-email-code" name="code"/>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Actions-->
                            <div class="d-flex flex-center">
                                <button type="reset" data-kt-element="email-cancel" class="btn btn-light me-3 btn-sm">بازگشت
                                </button>
                                {{-- 2 is mobile --}}
                                <span class="badge badge-success {{ $user->twoStepType(1) && $user->email_verified_at ? '' : 'd-none' }}">
                                    فعال می باشد
                                </span>
                                <button type="submit" data-kt-element="email-submit" class="btn btn-primary btn-sm {{ $user->email_verified_at ? 'd-none' : '' }}">
                                    <span class="indicator-label">تایید</span>
                                    <span class="indicator-progress">لطفا کمی صبر کنید
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <button onclick="selectStepType(1)" type="button" class="btn btn-primary btn-sm {{ $user->email_verified_at && ($user->twoStepType(2) || $user->twoStepType(3)) ? '' : 'd-none' }}">
                                    <span class="indicator-label">فعال شود</span>
                                    <span class="indicator-progress">لطفا کمی صبر کنید
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::EMAIL-->
                </div>
                <!--begin::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal header-->
    </div>
    <!--end::Modal - Two-factor authentication-->
@stop
@push('admin-js')

    <script>
        // Private variables
        var modal;
        var modalObject;

        var optionsWrapper;
        var optionsSelectButton;

        var smsWrapper;
        var smsForm;
        var smsSubmitButton;
        var smsCancelButton;
        var smsValidator;

        var emailWrapper;
        var emailForm;
        var emailSubmitButton;
        var emailCancelButton;
        var emailValidator;

        var appsWrapper;
        var appsForm;
        var appsSubmitButton;
        var appsCancelButton;
        var appsValidator;

        // Private functions
        var handleOptionsForm = function () {
            // Handle options selection
            optionsSelectButton.addEventListener('click', function (e) {
                e.preventDefault();
                var option = optionsWrapper.querySelector('[name="auth_option"]:checked');

                optionsWrapper.classList.add('d-none');
                // 2 is sms
                if (option.value == 2) {
                    smsWrapper.classList.remove('d-none');
                } else if (option.value == 1) { // 1 is email
                    emailWrapper.classList.remove('d-none');
                } else {
                    // else is google
                    appsWrapper.classList.remove('d-none');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.profile.generate.QRCode') }}",
                        success: function (response) {
                            $('#google-qrcode').html(response.google2fa_url);
                            $('#secret-key').html(`کد امنیتی : <span>${response.secret_key}</span>`);
                        },
                        error: function (err) {
                            Swal.fire({
                                text: "با عرض پوزش، به نظر می رسد برخی از خطاها شناسایی شده است، لطفا دوباره امتحان کنید.",
                                icon: "error",
                                buttonsStyling: !1,
                                showConfirmButton: false,
                                timer: 5000
                            })
                        }
                    });
                }
            });
        }

        var showOptionsForm = function () {
            optionsWrapper.classList.remove('d-none');
            smsWrapper.classList.add('d-none');
            appsWrapper.classList.add('d-none');
            emailWrapper.classList.add('d-none');
        }

        var handleSMSForm = function () {
            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            smsValidator = FormValidation.formValidation(
                smsForm,
                {
                    fields: {
                        'mobile': {
                            validators: {
                                notEmpty: {
                                    message: 'وارد کردن شماره موبایل الزامی است'
                                }
                            }
                        }
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: '',
                            eleValidClass: ''
                        })
                    }
                }
            );

            // Handle apps submition
            smsSubmitButton.addEventListener('click', function (e) {
                e.preventDefault();

                // Validate form before submit
                if (smsValidator) {
                    smsValidator.validate().then(function (status) {
                        console.log('validated!');

                        if (status == 'Valid') {
                            // Show loading indication
                            smsSubmitButton.setAttribute('data-kt-indicator', 'on');

                            // Disable button to avoid multiple click
                            smsSubmitButton.disabled = true;

                            // Simulate ajax process
                            setTimeout(function () {
                                // Remove loading indication
                                smsSubmitButton.removeAttribute('data-kt-indicator');

                                // Enable button
                                smsSubmitButton.disabled = false;

                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('admin.profile.verify.mobile') }}",
                                    data: {
                                        code: $('#form-verify-mobile-code').val(),
                                    },
                                    success: function (response) {
                                        toastr.success("احراز هویت دو مرحله ای با موبایل فعال شد");
                                        window.location.reload();
                                    },
                                    error: function (err) {
                                        Swal.fire({
                                            text: err.responseJSON.message,
                                            icon: "error",
                                            buttonsStyling: !1,
                                            showConfirmButton: false,
                                            timer: 5000
                                        })
                                    }
                                });

                            }, 2000);
                        } else {
                            // Show error message.
                            Swal.fire({
                                text: "Sorry, looks like there are some errors detected, please try again.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        }
                    });
                }
            });

            // Handle sms cancelation
            smsCancelButton.addEventListener('click', function (e) {
                e.preventDefault();
                var option = optionsWrapper.querySelector('[name="auth_option"]:checked');

                optionsWrapper.classList.remove('d-none');
                smsWrapper.classList.add('d-none');
            });
        }

        var handleEMAILForm = function () {
            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            emailValidator = FormValidation.formValidation(
                emailForm,
                {
                    fields: {
                        'code': {
                            validators: {
                                notEmpty: {
                                    message: 'وارد کردن کد تایید الزامی است'
                                }
                            }
                        }
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: '',
                            eleValidClass: ''
                        })
                    }
                }
            );

            // Handle apps submition
            emailSubmitButton.addEventListener('click', function (e) {
                e.preventDefault();

                // Validate form before submit
                if (emailValidator) {
                    emailValidator.validate().then(function (status) {
                        console.log('validated!');

                        if (status == 'Valid') {
                            // Show loading indication
                            emailSubmitButton.setAttribute('data-kt-indicator', 'on');

                            // Disable button to avoid multiple click
                            emailSubmitButton.disabled = true;

                            // Simulate ajax process
                            setTimeout(function () {
                                // Remove loading indication
                                emailSubmitButton.removeAttribute('data-kt-indicator');

                                // Enable button
                                emailSubmitButton.disabled = false;
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('admin.profile.verify.email') }}",
                                    data: {
                                        code: $('#form-verify-email-code').val(),
                                    },
                                    success: function (response) {
                                        window.location.reload();
                                        toastr.success("احراز هویت دو مرحله ای با ایمیل فعال شد");
                                    },
                                    error: function (err) {
                                        Swal.fire({
                                            text: err.responseJSON.message,
                                            icon: "error",
                                            buttonsStyling: !1,
                                            showConfirmButton: false,
                                            timer: 5000
                                        })
                                    }
                                });
                            }, 2000);
                        } else {
                            // Show error message.
                            Swal.fire({
                                text: "با عرض پوزش، به نظر می رسد برخی از خطاها شناسایی شده است، لطفا دوباره امتحان کنید.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "بسیار خوب",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        }
                    });
                }
            });

            // Handle sms cancelation
            emailCancelButton.addEventListener('click', function (e) {
                e.preventDefault();
                var option = optionsWrapper.querySelector('[name="auth_option"]:checked');

                optionsWrapper.classList.remove('d-none');
                emailWrapper.classList.add('d-none');
            });
        }

        function activeTwoSetpType() {
            var option = optionsWrapper.querySelector('[name="auth_option"]:checked');

            $('#twoStepMethodType').val(option.value);
        }

        var handleAppsForm = function () {
            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            appsValidator = FormValidation.formValidation(
                appsForm,
                {
                    fields: {
                        'code': {
                            validators: {
                                notEmpty: {
                                    message: 'وارد کردن کد تایید الزامی است'
                                }
                            }
                        }
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: '',
                            eleValidClass: ''
                        })
                    }
                }
            );

            // Handle apps submition
            appsSubmitButton.addEventListener('click', function (e) {
                e.preventDefault();

                // Validate form before submit
                if (appsValidator) {
                    appsValidator.validate().then(function (status) {
                        console.log('validated!');

                        if (status == 'Valid') {
                            appsSubmitButton.setAttribute('data-kt-indicator', 'on');

                            // Disable button to avoid multiple click
                            appsSubmitButton.disabled = true;

                            setTimeout(function () {
                                appsSubmitButton.removeAttribute('data-kt-indicator');

                                // Enable button
                                appsSubmitButton.disabled = false;

                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('admin.profile.verify.google') }}",
                                    data: {
                                        code: $('#form-google-code-verify').val(),
                                    },
                                    success: function (response) {
                                        window.location.reload();
                                        toastr.success("احراز هیت دو مرحله ای با گوگل فعال شد");
                                    },
                                    error: function (err) {
                                        Swal.fire({
                                            text: err.responseJSON.message,
                                            icon: "error",
                                            buttonsStyling: !1,
                                            showConfirmButton: false,
                                            timer: 5000
                                        })
                                    }
                                });

                            }, 2000);
                        } else {
                            // Show error message.
                            Swal.fire({
                                text: "Sorry, looks like there are some errors detected, please try again.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        }
                    });
                }
            });

            // Handle apps cancelation
            appsCancelButton.addEventListener('click', function (e) {
                e.preventDefault();
                var option = optionsWrapper.querySelector('[name="auth_option"]:checked');

                optionsWrapper.classList.remove('d-none');
                appsWrapper.classList.add('d-none');
            });
        }

        // Elements
        modal = document.querySelector('#kt_modal_two_factor_authentication');

        modalObject = new bootstrap.Modal(modal);

        optionsWrapper = modal.querySelector('[data-kt-element="options"]');
        optionsSelectButton = modal.querySelector('[data-kt-element="options-select"]');

        smsWrapper = modal.querySelector('[data-kt-element="sms"]');
        smsForm = modal.querySelector('[data-kt-element="sms-form"]');
        smsSubmitButton = modal.querySelector('[data-kt-element="sms-submit"]');
        smsCancelButton = modal.querySelector('[data-kt-element="sms-cancel"]');

        emailWrapper = modal.querySelector('[data-kt-element="email"]');
        emailForm = modal.querySelector('[data-kt-element="email-form"]');
        emailSubmitButton = modal.querySelector('[data-kt-element="email-submit"]');
        emailCancelButton = modal.querySelector('[data-kt-element="email-cancel"]');

        appsWrapper = modal.querySelector('[data-kt-element="apps"]');
        appsForm = modal.querySelector('[data-kt-element="apps-form"]');
        appsSubmitButton = modal.querySelector('[data-kt-element="apps-submit"]');
        appsCancelButton = modal.querySelector('[data-kt-element="apps-cancel"]');

        // Handle forms
        handleOptionsForm();
        handleSMSForm();
        handleEMAILForm();
        handleAppsForm();


        $('#twoStepMethodStatus').on('change', function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{ route('admin.profile.active.two.step') }}",
                data: {status:$(this).prop('checked') ? 1 : 0},
                success: function (response) {
                    if (response.message) {
                        toastr.success(response.message);
                    }
                },
                error: function (err) {

                }
            });
            $("#twoStepMethod").toggle();
        })


        //send code
        function verifyEmailCode() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{ route('admin.profile.verify.email.code') }}",
                success: function (response) {
                    toastr.success("کد تایید به ایمیل شما ارسال شد");
                },
                error: function (err) {
                    Swal.fire({
                        text: err.responseJSON.message,
                        icon: "error",
                        buttonsStyling: !1,
                        showConfirmButton: false,
                        timer: 5000
                    })
                }
            });
        }
        //send code
        function verifyMobileCode() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{ route('admin.profile.verify.mobile.code') }}",
                success: function (response) {
                    toastr.success("کد تایید به شماره موبایل شما ارسال شد");
                },
                error: function (err) {
                    Swal.fire({
                        text: err.responseJSON.message,
                        icon: "error",
                        buttonsStyling: !1,
                        showConfirmButton: false,
                        timer: 5000
                    })
                }
            });
        }

        function confirmTimer(index) {
            $('#timerCounterDown-' + index).removeClass('d-none')
            $('#confirmTimerButton-' + index).addClass('d-none')

            var start = Date.now(), r = document.getElementById('timerCounterDown-' + index);
            (function f() {
                var diff = Date.now() - start, ns = (((6e4 - diff) / 1000) >> 0), m = (ns / 60) >> 0, s = ns - m * 60;
                r.textContent = m + ':' + (('' + s).length > 1 ? '' : '0') + s + ' دقیقه تا ارسال مجدد کد';
                if (diff > (6e4)) {
                    start = Date.now()
                    $('#timerCounterDown-' + index).addClass('d-none')
                    $('#confirmTimerButton-' + index).removeClass('d-none')
                }
                setTimeout(f, 1000);
            })();
        }

        function selectStepType(type) {
            let url = "{{ route('admin.profile.active.unactive.two.step.type') }}?type=" + type;
            location.assign(url);
        }
    </script>
@endpush
