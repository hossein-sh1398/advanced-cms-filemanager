@extends('auth.theme.master')

@section('auth.content')
    <!--begin::Form-->
    <form class="form w-100" method="post" action="{{route('auth.forgot.link')}}" novalidate="novalidate" id="kt_password_reset_form">
        <!--begin::Heading-->
        @csrf
        <div class="text-center mb-10">
            <!--begin::Title-->
            <h1 class="text-dark mb-3">رمز عبور خود را فراموش کرده اید ؟</h1>
            <!--end::Title-->
            <!--begin::Link-->
            <div class="text-gray-400 fw-bold fs-4">لطفا ایمیل خود را وارد نمایید.</div>
            <!--end::Link-->
        </div>
        <!--begin::Heading-->
        <!--begin::Input group-->
        <div class="fv-row mb-10">
            <label class="form-label fw-bolder text-gray-900 fs-6" for="email">ایمیل</label>
            <input class="form-control form-control-solid" value="{{old('email')}}" type="email" placeholder=""
                   id="email" name="email" autocomplete="off"/>
            @error('email')
                <div class="fv-plugins-message-container invalid-feedback">
                    <div data-field="email" data-validator="notEmpty">{{$message}}</div>
                </div>
            @enderror
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
        <div class="d-flex flex-wrap justify-content-center pb-lg-0">
            <button type="button" id="kt_password_reset_submit" class="btn btn-lg btn-primary fw-bolder me-4">
                <span class="indicator-label">ارسال</span>
                <span class="indicator-progress">لطفا کمی صبر کنید...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
            <a href="{{ route('auth.login') }}" class="btn btn-lg btn-light-primary fw-bolder">بازگشت</a>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->
@stop
@push('auth-js')
    <script>
        "use strict";
        var KTPasswordResetGeneral = function () {
            var t, e, i;
            return {
                init: function () {
                    t = document.querySelector("#kt_password_reset_form");
                    e = document.querySelector("#kt_password_reset_submit");
                    i = FormValidation.formValidation(t, {
                        fields: {
                            email: {
                                validators: {
                                    notEmpty: {message: "ایمیل اجباری میباشد"},
                                    emailAddress: {message: "ایمیل انتخاب شده معتبر نیست"}
                                }
                            },
                            "g-recaptcha-response": {
                                validators: {
                                    notEmpty: {message: "انتخاب فیلد کپچا اجباری است"},
                                }
                            }
                        },
                        plugins: {
                            trigger: new FormValidation.plugins.Trigger,
                            bootstrap: new FormValidation.plugins.Bootstrap5({
                                rowSelector: ".fv-row",
                                eleInvalidClass: "",
                                eleValidClass: ""
                            })
                        }
                    })
                    e.addEventListener("click", (function (o) {
                        o.preventDefault();
                        i.validate().then((function (i) {
                            if ("Valid" == i) {
                                e.setAttribute("data-kt-indicator", "on");
                                e.disabled = !0;
                                setTimeout((function () {
                                    e.removeAttribute("data-kt-indicator");
                                    e.disabled = !1;
                                    $('#kt_password_reset_form').submit();
                                }), 1500)
                            } else {
                                Swal.fire({
                                    text: "با عرض پوزش، به نظر می رسد برخی از خطاها شناسایی شده است، لطفا دوباره امتحان کنید.",
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "باشه فهمیدم!",
                                    customClass: {confirmButton: "btn btn-primary"}
                                })
                            }
                        }))
                    }))
                }
            }
        }();
        KTUtil.onDOMContentLoaded((function () {
            KTPasswordResetGeneral.init()
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
