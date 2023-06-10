@extends('auth.theme.master')

@section('auth.content')
    <!--begin::Form-->
    <form class="form w-100" method="post" action="{{ route('auth.two.step.authentication.verify') }}" novalidate="novalidate" id="kt_sign_in_form">
        @csrf
        <!--begin::Heading-->
        <div class="text-center mb-10">
            <!--begin::Title-->
            <h1 class="text-dark mb-3">تایید کد پویا</h1>
            <!--end::Title-->
        </div>
        <!--begin::Heading-->
        <!--begin::Input group-->
        <div class="fv-row mb-10">
            <!--begin::Label-->
            <label class="form-label fs-6 fw-bolder text-dark" for="username">کد پویا</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input class="form-control form-control-lg form-control-solid" value="{{old('code')}}" type="text" name="code" id="code"
                autocomplete="off" />
            @error('code')
                <div class="fv-plugins-message-container invalid-feedback">
                    <div data-field="username-error" data-validator="notEmpty">{{$message}}</div>
                </div>
            @enderror
            <!--end::Input-->
        </div>
        <!--end::Input group-->
        <!--begin::Actions-->
        <div class="text-center">
            <!--begin::Submit button-->
            <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                <span class="indicator-label">تایید و ورود</span>
                <span class="indicator-progress">لطفا کمی صبر کنید...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
            <a href="{{ route('auth.login') }}" class="btn btn-lg btn-light-primary fw-bolder">بازگشت</a>
            <!--end::Submit button-->
        </div>
        <!--end::Actions-->
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
                            code: {
                                validators: {
                                    notEmpty: {
                                        message: "رمز پویا اجباری میباشد"
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
    </script>
@endpush

