@extends('auth.theme.master')

@section('auth.content')
    <!--begin::Form-->
    <form class="form w-100" method="POST" action="{{ route('auth.register') }}" novalidate="novalidate" id="kt_sign_up_form"
        data-kt-redirect-url="{{ route('auth.login') }}">
        @csrf
        <!--begin::Heading-->
        <div class="mb-10 text-center">
            <!--begin::Title-->
            <h1 class="text-dark mb-3">ساخت اکانت جدید</h1>
            <!--end::Title-->
            <!--begin::Link-->
            <div class="text-gray-400 fw-bold fs-4">آیا اکانت دارید ؟
                <a href="{{ route('auth.login') }}" class="link-primary fw-bolder">ورود به حساب کاربری</a>
            </div>
            <!--end::Link-->
        </div>
        <!--end::Heading-->
        <!--begin::Separator-->
        <div class="d-flex align-items-center mb-10">
            <div class="border-bottom border-gray-300 mw-50 w-100"></div>
            <span class="fw-bold text-gray-400 fs-7 mx-2">یا</span>
            <div class="border-bottom border-gray-300 mw-50 w-100"></div>
        </div>
        <!--end::Separator-->
        <!--begin::Input group-->
        <div class="row fv-row mb-7">
            <label class="form-label fw-bolder text-dark fs-6" for="name">نام</label>
            <input class="form-control form-control-lg form-control-solid" value="{{old('name')}}" type="text" placeholder="" name="name"
                id="name" autocomplete="off" />
            @error('name')
                <div class="fv-plugins-message-container invalid-feedback">
                    <div data-field="username-error" data-validator="notEmpty">{{$message}}</div>
                </div>
            @enderror
            <!--end::Col-->
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="form-label fw-bolder text-dark fs-6" for="email">ایمیل</label>
            <input class="form-control form-control-lg form-control-solid" value="{{old('email')}}" type="email" placeholder="" name="email"
                id="email" autocomplete="off" />
            @error('email')
                <div class="fv-plugins-message-container invalid-feedback">
                    <div data-field="username-error" data-validator="notEmpty">{{$message}}</div>
                </div>
            @enderror
            <div id="email-error"></div>
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="form-label fw-bolder text-dark fs-6" for="mobile">شماره موبایل</label>
            <input class="form-control form-control-lg form-control-solid" value="{{old('mobile')}}" type="text" dir="ltr" placeholder="" name="mobile"
                id="mobile" autocomplete="off" />
            @error('mobile')
                <div class="fv-plugins-message-container invalid-feedback">
                    <div data-field="username-error" data-validator="notEmpty">{{$message}}</div>
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
                        id="password" name="password" autocomplete="off" />
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
        <!--begin::Input group-->
        <div class="fv-row mb-5">
            <label class="form-label fw-bolder text-dark fs-6" for="password_confirmation">تایید رمز عبور</label>
            <input class="form-control form-control-lg form-control-solid" type="password" placeholder=""
                name="password_confirmation" id="password_confirmation" autocomplete="off" />
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        @if ($toc)
            <div class="fv-row mb-10">
                <label class="form-check form-check-custom form-check-solid form-check-inline">
                    <input class="form-check-input" type="checkbox" name="toc" {{old('toc') ? 'checked' : ''}} value="1" id="toc" />
                    @error('toc')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="toc" data-validator="notEmpty">{{$message}}</div>
                        </div>
                    @enderror
                    <span class="form-check-label fw-bold text-gray-700 fs-6">من موافقم با
                        <a href="#" class="ms-1 link-primary" data-bs-toggle="modal" data-bs-target="#accepted-rules-modal">شرایط و ضوابط</a>.
                    </span>
                </label>
            </div>
        @endif
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
            <button type="button" id="kt_sign_up_submit" class="btn btn-lg btn-primary">
                <span class="indicator-label">ثبت نام</span>
                <span class="indicator-progress">لطفا کمی صبر کنید...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->
    @if ($toc)
        <div class="modal modal-lg fade" tabindex="-1" id="accepted-rules-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">شرایط و ضوابط</h5>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-2x"></span>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body">
                        {{ $toc }}
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop

@push('auth-js')
    <script>
        "use strict";
        var KTSignupGeneral = function() {
            var e, t, a, s, r = function() {
                return 100 === s.getScore()
            };
            return {
                init: function() {
                    e = document.querySelector("#kt_sign_up_form");
                    t = document.querySelector("#kt_sign_up_submit");
                    s = KTPasswordMeter.getInstance(e.querySelector('[data-kt-password-meter="true"]'));
                    a = FormValidation.formValidation(e, {
                        fields: {
                            name: {
                                validators: {
                                    notEmpty: {
                                        message: "نام اجباری میباشد"
                                    },
                                    stringLength: {
                                        min: 3,
                                        max: 255,
                                        message: 'تعداد حروف نام باید بیشتر از 3 و کمتر از 255 حرف باشد'
                                    },
                                }
                            },
                            email: {
                                validators: {
                                    notEmpty: {
                                        message: "ایمیل اجباری میباشد"
                                    },
                                    emailAddress: {
                                        message: "ایمیل انتخاب شده معتبر نیست"
                                    }
                                }
                            },
                            mobile: {
                                validators: {
                                    notEmpty: {
                                        message: "شماره موبایل اجباری میباشد"
                                    },
                                    regexp: {
                                        regexp: /^[0]{1}[9]{1}\d{9}$/,
                                        message: 'فرمت شماره موبایل صحیح نمی باشد'
                                    },
                                }
                            },
                            password: {
                                validators: {
                                    notEmpty: {
                                        message: "رمز عبور اجباری میباشد"
                                    },
                                    // callback: {
                                    //     message: "لطفا پسورد معتبر انتخاب نمایید",
                                    //     callback: function(e) {
                                    //         if (e.value.length > 0) return r()
                                    //     }
                                    // }
                                }
                            },
                            password_confirmation: {
                                validators: {
                                    notEmpty: {
                                        message: "تایید رمز عبور اجباری میباشد"
                                    },
                                    identical: {
                                        compare: function() {
                                            return e.querySelector('[name="password"]').value
                                        },
                                        message: "تایید رمز عبور مطابقت ندارد"
                                    }
                                }
                            },
                            toc: {
                                validators: {
                                    notEmpty: {
                                        message: "جهت ثبت نام شما باید قوانین را بپذیرید"
                                    }
                                }
                            }
                        },
                        plugins: {
                            trigger: new FormValidation.plugins.Trigger({
                                event: {
                                    password: !1
                                }
                            }),
                            bootstrap: new FormValidation.plugins.Bootstrap5({
                                rowSelector: ".fv-row",
                                eleInvalidClass: "",
                                eleValidClass: ""
                            })
                        }
                    })
                    t.addEventListener("click", (function(r) {
                        r.preventDefault();
                        a.revalidateField("password");
                        a.validate().then((function(a) {
                            if ("Valid" == a) {
                                t.setAttribute("data-kt-indicator", "on");
                                t.disabled = !0;
                                setTimeout((function() {
                                    t.removeAttribute("data-kt-indicator");
                                    t.disabled = !1;
                                    $('#kt_sign_up_form').submit();
                                    // $.ajax({
                                    //     type: "POST",
                                    {{--//     url: '{{ route('register') }}',--}}
                                    //     cache: false,
                                    //     data: {
                                    //         "first_name": document
                                    //             .getElementById(
                                    //                 'first_name')
                                    //             .value,
                                    //         "last_name": document
                                    //             .getElementById(
                                    //                 'last_name')
                                    //             .value,
                                    //         "email": document
                                    //             .getElementById(
                                    //                 'email')
                                    //             .value,
                                    //         "mobile": document
                                    //             .getElementById(
                                    //                 'mobile')
                                    //             .value,
                                    //         "password": document
                                    //             .getElementById(
                                    //                 'password').value,
                                    //         "password_confirmation": document
                                    //             .getElementById(
                                    //                 'password_confirmation'
                                    //             )
                                    //             .value,
                                    //         "toc": document
                                    //             .getElementById('toc')
                                    //             .value,
                                    {{--//         "_token": "{{ csrf_token() }}",--}}
                                    //     },
                                    //     success: function(response) {
                                    //         if (response.status ===
                                    //             'ok') {
                                    //             var i = e
                                    //                 .getAttribute(
                                    //                     "data-kt-redirect-url"
                                    //                 );
                                    //             i && (location
                                    //                 .href = i)
                                    //         } else {

                                    //         }
                                    //     },
                                    //     error: function(error) {
                                    //         let fiels = ['email',
                                    //             'mobile'
                                    //         ];
                                    //         for (field of fiels) {
                                    //             document
                                    //                 .getElementById(
                                    //                     field).in
                                    //         }
                                    //         console.log(error
                                    //             .responseJSON
                                    //             .errors);
                                    //         Swal.fire({
                                    //             text: "با عرض پوزش، به نظر می رسد برخی از خطاها شناسایی شده است، لطفا دوباره امتحان کنید.",
                                    //             icon: "error",
                                    //             buttonsStyling:
                                    //                 !1,
                                    //             confirmButtonText: "باشه فهمیدم!",
                                    //             customClass: {
                                    //                 confirmButton: "btn btn-primary"
                                    //             }
                                    //         })
                                    //     }
                                    // });
                                }), 1500)
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
                    e.querySelector('input[name="password"]').addEventListener("input", (function() {
                        this.value.length > 0 && a.updateFieldStatus("password", "NotValidated")
                    }))
                }
            }
        }();
        KTUtil.onDOMContentLoaded((function() {
            KTSignupGeneral.init()
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
