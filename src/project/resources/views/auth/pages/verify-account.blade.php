@extends('auth.theme.master')

@section('auth.content')
    <!--begin::Form-->
    <form class="form w-100" method="post" action="{{route('auth.verify.account')}}" novalidate="novalidate"
          id="kt_password_reset_form">
        <!--begin::Heading-->
        @csrf
        <div class="text-center mb-10">
            <!--begin::Title-->
            <h1 class="text-dark mb-3">حساب خود را تایید نمایید</h1>
            <!--end::Title-->
        </div>
        <div class="fv-row mb-10">
            <div class="form-group{{ $errors->has('sms_code') ? ' has-error' : '' }}">
                <label for="sms_code" class="control-label">کد تایید</label>
                <input id="sms_code" name="sms_code" class="form-control col-md-4"
                       type="text" required/>
                @error('sms_code')
                <div class="fv-plugins-message-container invalid-feedback">
                    <div data-field="sms_code" data-validator="notEmpty">{{$message}}</div>
                </div>
                @enderror
            </div>
            <!--end::Input-->
        </div>
        <!--begin::Heading-->
        <!--begin::Actions-->
        <div class="d-flex flex-wrap justify-content-center pb-lg-0 mb-10">
            <button type="submit" class="btn btn-lg btn-primary" style="margin-left: 6px;">تایید</button>
        </div>
        <div class="d-flex flex-wrap justify-content-center pb-lg-0">
            <a href="{{route('auth.account.verify.re.send.code')}}" class="fw-bolder me-4">ارسال کد تایید به تلفن همراه و ایمیل</a>
            <a href="{{route('auth.logout')}}" class="fw-bolder me-4">خروج</a>
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
                            sms_code: {
                                validators: {
                                    notEmpty: {
                                        message: "رمز پویا اجباری میباشد"
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
                                    {{--$.ajax({--}}
                                    {{--    type: "POST",--}}
                                    {{--    url: '{{ route('auth.forget') }}',--}}
                                    {{--    cache: false,--}}
                                    {{--    data: {--}}
                                    {{--        "email": document.getElementById('email').value,--}}
                                    {{--        "_token": "{{ csrf_token() }}",--}}
                                    {{--    },--}}
                                    {{--    success: function (response) {--}}
                                    {{--        Swal.fire({--}}
                                    {{--            text: "ایمیل بازیابی با موفقیت ارسال گردید",--}}
                                    {{--            icon: "success",--}}
                                    {{--            buttonsStyling: !1,--}}
                                    {{--            confirmButtonText: "باشه فهمیدم!",--}}
                                    {{--            customClass: {confirmButton: "btn btn-primary"}--}}
                                    {{--        }).then((function (e) {--}}
                                    {{--            e.isConfirmed && (t.querySelector('[name="email"]').value = "")--}}
                                    {{--        }))--}}
                                    {{--    },--}}
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
                    }))
                }
                }
            }();
            KTUtil.onDOMContentLoaded((function () {
                KTPasswordResetGeneral.init()
            }));
    </script>
@endpush
