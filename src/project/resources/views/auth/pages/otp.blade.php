@extends('auth.theme.master')

@section('auth.content')
    <!--begin::Form-->
    <form class="form w-100" method="post" action="{{ route('auth.login.send.code') }}" novalidate="novalidate" id="kt_sign_in_form">
        @csrf
        <!--begin::Heading-->
        <div class="text-center mb-10">
            <!--begin::Title-->
            <h1 class="text-dark mb-3">ورود با رمز پویا</h1>
            <!--end::Title-->
            <!--begin::Link-->
            @if ($accessRegister)
                <div class="text-gray-400 fw-bold fs-4">آیا اکانت ندارید ؟
                    <a href="{{ route('auth.register.form') }}" class="link-primary fw-bolder">ساخت اکانت جدید</a>
                </div>
            @endif
            <!--end::Link-->
        </div>
        <!--begin::Heading-->
        <!--begin::Input group-->
        <div class="fv-row mb-10">
            <!--begin::Label-->
            <label class="form-label fs-6 fw-bolder text-dark" for="username">نام کاربری شامل شماره موبایل یا ایمیل</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input class="form-control form-control-lg form-control-solid" value="{{old('username')}}" type="text" name="username" id="username"
                autocomplete="off" />
            @error('username')
                <div class="fv-plugins-message-container invalid-feedback">
                    <div data-field="username-error" data-validator="notEmpty">{{$message}}</div>
                </div>
            @enderror
            <!--end::Input-->
        </div>
        <!--end::Input group-->
        <div class="fv-row mb-10">
            <!--begin::Wrapper-->
            <div id="loginFormRecaptcha" class="g-recaptcha-center"></div>
            @error('g-recaptcha-response')
                <div class="fv-plugins-message-container invalid-feedback">
                    <div data-field="g-recaptcha-response" data-validator="notEmpty">{{$message}}</div>
                </div>
            @enderror
            <!--end::Input-->
        </div>
        <!--begin::Actions-->
        <div class="text-center">
            <!--begin::Submit button-->
            <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                <span class="indicator-label">ورود</span>
                <span class="indicator-progress">لطفا کمی صبر کنید...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
            <!--end::Submit button-->
        </div>
        <!--end::Actions-->

        <div class="fv-row">
            <div class="d-flex flex-stack mb-2">
                <a href="{{route('auth.login')}}" class="link-primary fs-6 fw-bolder">ورود با رمز ثابت</a>
            </div>
        </div>
    </form>
    <!--end::Form-->
@stop

@push('auth-js')
    <script>
        "use strict";
        var KTSigninGeneral = function() {
            var t, e, i;
            return {
                init: function() {
                    t = document.querySelector("#kt_sign_in_form");
                    e = document.querySelector("#kt_sign_in_submit");
                    i = FormValidation.formValidation(t, {
                        fields: {
                            username: {
                                validators: {
                                    notEmpty: {
                                        message: "نام کاربری اجباری میباشد"
                                    },
                                    usernameAddress: {
                                        message: "نام کاربری وارد شده صحیح نمی باشد."
                                    }
                                }
                            }
                        },
                        plugins: {
                            trigger: new FormValidation.plugins.Trigger,
                            bootstrap: new FormValidation.plugins.Bootstrap5({
                                rowSelector: ".fv-row"
                            })
                        }
                    });
                    e.addEventListener("click", (function(n) {
                        n.preventDefault();
                        i.validate().then((function(i) {
                            if ("Valid" == i) {
                                e.setAttribute("data-kt-indicator", "on");
                                e.disabled = !0;
                                setTimeout((function() {
                                    e.removeAttribute("data-kt-indicator")
                                    e.disabled = !1;
                                    $('#kt_sign_in_form').submit();
                                }), 2e3)
                            } else {
                                Swal.fire({
                                    text: "با عرض پوزش، به نظر می رسد برخی از خطاها شناسایی شده است، لطفا دوباره امتحان کنید.",
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "باشه فهمیدم!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                })
                            }
                        }))
                    }))
                }
            }
        }();
        KTUtil.onDOMContentLoaded((function() {
            KTSigninGeneral.init()
        }));

        var sitekey = "{{$apiRecaptchaClient}}";

        var onloadCallback = function () {
            if ($('#loginFormRecaptcha').length) {
                loginForm = grecaptcha.render('loginFormRecaptcha', {
                    'sitekey': sitekey
                });
            }
        };
    </script>

    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=fa" async defer></script>
@endpush

