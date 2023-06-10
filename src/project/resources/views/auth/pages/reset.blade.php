@extends('auth.theme.master')

@section('auth.content')
    <!--begin::Form-->
    <form class="form w-100" method="post" novalidate="novalidate" id="kt_new_password_form" action="{{ route('auth.password.update') }}" data-kt-redirect-url="{{ route('auth.password.update') }}">
        @csrf
        <!--begin::Heading-->
        <div class="text-center mb-10">
            <!--begin::Title-->
            <h1 class="text-dark mb-3">تنظیم رمز عبور جدید</h1>
            <!--end::Title-->
            <!--begin::Link-->
            <div class="text-gray-400 fw-bold fs-4">قبلاً رمز عبور خود را بازنشانی کرده اید ؟
                <a href="{{ route('auth.login') }}" class="link-primary fw-bolder">ورود به حساب کاربری</a></div>
            <!--end::Link-->
        </div>
        <!--begin::Heading-->
        <input type="hidden" name="token" value="{{$token}}">
        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="form-label fw-bolder text-dark fs-6" for="email">ایمیل</label>
            <input class="form-control form-control-lg form-control-solid"
                   type="email" name="email" value="{{ request()->query('email') }}" id="email" autocomplete="off"/>
            @error('email')
                <div class="fv-plugins-message-container invalid-feedback">
                    <div data-field="email" data-validator="notEmpty">{{$message}}</div>
                </div>
            @enderror
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="mb-10 fv-row" data-kt-password-meter="true">
            <!--begin::Wrapper-->
            <div class="mb-1">
                <!--begin::Label-->
                <label class="form-label fw-bolder text-dark fs-6" for="password">رمز عبور</label>
                <!--end::Label-->
                <!--begin::Input wrapper-->
                <div class="position-relative mb-3">
                    <input class="form-control form-control-lg form-control-solid" type="password" placeholder=""
                           name="password" id="password" autocomplete="off"/>
                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                          data-kt-password-meter-control="visibility">
					    <i class="bi bi-eye-slash fs-2"></i>
					    <i class="bi bi-eye fs-2 d-none"></i>
                    </span>
                    @error('password')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="password" data-validator="notEmpty">{{$message}}</div>
                        </div>
                    @enderror
                </div>
                <!--end::Input wrapper-->
                <!--begin::Meter-->
                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                </div>
                <!--end::Meter-->
            </div>
            <!--end::Wrapper-->
            <!--begin::Hint-->
            <div class="text-muted">از 8 یا بیشتر کاراکتر با ترکیبی از حروف، اعداد و نمادها</div>
            <!--end::Hint-->
        </div>
        <!--end::Input group=-->
        <!--begin::Input group=-->
        <div class="fv-row mb-10">
            <label class="form-label fw-bolder text-dark fs-6" for="password_confirmation">تایید رمز عبور</label>
            <input class="form-control form-control-lg form-control-solid" type="password" placeholder=""
                   name="password_confirmation" id="password_confirmation" autocomplete="off"/>
        </div>
        <!--end::Input group=-->
        <!--begin::Action-->
        <div class="text-center">
            <button type="button" id="kt_new_password_submit" class="btn btn-lg btn-primary fw-bolder">
                <span class="indicator-label">ذخیره</span>
                <span class="indicator-progress">لطفا کمی صبر کنید...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
        <!--end::Action-->
    </form>
    <!--end::Form-->
@stop
@push('auth-js')
    <script>
        "use strict";
        var KTPasswordResetNewPassword = function () {
            var e, t, r, o, s = function () {
                return 100 === o.getScore()
            };
            return {
                init: function () {
                    e = document.querySelector("#kt_new_password_form");
                    t = document.querySelector("#kt_new_password_submit");
                    o = KTPasswordMeter.getInstance(e.querySelector('[data-kt-password-meter="true"]'));
                    r = FormValidation.formValidation(e, {
                        fields: {
                            password: {
                                validators: {
                                    notEmpty: {message: "رمز عبور اجباری میباشد"},
                                    // callback: {
                                    //     message: "لطفا پسورد معتبر انتخاب نمایید", callback: function (e) {
                                    //         if (e.value.length > 0) return s()
                                    //     }
                                    // }
                                }
                            },
                            "confirm-password": {
                                validators: {
                                    notEmpty: {message: "تایید رمز عبور اجباری میباشد"},
                                    identical: {
                                        compare: function () {
                                            return e.querySelector('[name="password"]').value
                                        }, message: "تایید رمز عبور مطابقت ندارد"
                                    }
                                }
                            }
                        },
                        plugins: {
                            trigger: new FormValidation.plugins.Trigger({event: {password: !1}}),
                            bootstrap: new FormValidation.plugins.Bootstrap5({
                                rowSelector: ".fv-row",
                                eleInvalidClass: "",
                                eleValidClass: ""
                            })
                        }
                    });
                    t.addEventListener("click", (function (s) {
                        s.preventDefault();
                        r.revalidateField("password");
                        r.validate().then((function (r) {
                            if ("Valid" == r) {
                                t.setAttribute("data-kt-indicator", "on");
                                t.disabled = !0;
                                setTimeout((function () {
                                    t.removeAttribute("data-kt-indicator");
                                    t.disabled = !1;
                                    $('#kt_new_password_form').submit();
                                    {{--$.ajax({--}}
                                    {{--    type: "POST",--}}
                                    {{--    url: '{{ route('auth.reset') }}',--}}
                                    {{--    cache: false,--}}
                                    {{--    data: {--}}
                                    {{--        "token": '{{ request()->input('token') }}',--}}
                                    {{--        "email": '{{ request()->input('email') }}',--}}
                                    {{--        "password": document.getElementById('password').value,--}}
                                    {{--        "confirm-password": document.getElementById('confirm-password').value,--}}
                                    {{--        "_token": "{{ csrf_token() }}",--}}
                                    {{--    },--}}
                                    {{--    success: function (response) {--}}
                                    {{--        Swal.fire({--}}
                                    {{--            text: "رمز عبور شما با موفقیت تغییر کرد",--}}
                                    {{--            icon: "success",--}}
                                    {{--            buttonsStyling: !1,--}}
                                    {{--            confirmButtonText: "صفحه ورود",--}}
                                    {{--            customClass: {confirmButton: "btn btn-primary"}--}}
                                    {{--        }).then((function (t) {--}}
                                    {{--            t.isConfirmed && (e.querySelector('[name="password"]').value = "", e.querySelector('[name="confirm-password"]').value = "", o.reset())--}}
                                    {{--            var i = e.getAttribute("data-kt-redirect-url");--}}
                                    {{--            i && (location.href = i)--}}
                                    {{--        }))                                        },--}}
                                    {{--    error: function (error) {--}}
                                    {{--        Swal.fire({--}}
                                    {{--            text: "با عرض پوزش، به نظر می رسد برخی از خطاها شناسایی شده است، لطفا دوباره امتحان کنید.",--}}
                                    {{--            icon: "error",--}}
                                    {{--            buttonsStyling: !1,--}}
                                    {{--            confirmButtonText: "باشه فهمیدم!",--}}
                                    {{--            customClass: {confirmButton: "btn btn-primary"}--}}
                                    {{--        })--}}
                                    {{--    }--}}
                                    {{--});--}}

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
                    }));
                    e.querySelector('input[name="password"]').addEventListener("input", (function () {
                        this.value.length > 0 && r.updateFieldStatus("password", "NotValidated")
                    }))
                }
            }
        }();
        KTUtil.onDOMContentLoaded((function () {
            KTPasswordResetNewPassword.init()
        }));
    </script>
@endpush
