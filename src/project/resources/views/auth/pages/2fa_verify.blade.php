@extends('auth.theme.master')

@section('auth.content')
    <!--begin::Form-->
    <form class="form w-100" method="post" action="{{ route('2faVerify') }}" novalidate="novalidate">
        @csrf
        <!--begin::Heading-->
        <div class="text-center mb-10">
            <!--begin::Title-->
            <h1 class="text-dark mb-3">سیستم ورود کاربران </h1>
            <!--end::Title-->
        </div>
        @foreach($errors->all() as $e)
                <p class="alert alert-danger">{{$e}}</p>
        @endforeach
        <div class="text-center mb-10">
            <!--begin::Title-->
            <h6 class="text-dark mb-3">پین را از برنامه Google Authenticator وارد کنید</h6>
            <!--end::Title-->
        </div>
        <!--begin::Heading-->
        <!--begin::Input group-->
        <div class="fv-row mb-10">
            <div class="form-group{{ $errors->has('one_time_password') ? ' has-error' : '' }}">
                <label for="one_time_password" class="control-label">کد تایید</label>
                <input id="one_time_password" name="one_time_password" class="form-control col-md-4"
                       type="text" required />
            </div>
            <!--end::Input-->
        </div>

        <!--end::Input group-->
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
    </form>
    <!--end::Form-->
@stop
