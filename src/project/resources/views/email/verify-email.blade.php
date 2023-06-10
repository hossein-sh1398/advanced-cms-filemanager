@extends('admin.theme.master-email')
@section('title')
    تایید ایمیل
@endsection
@section('main-content')
    <div style="padding-bottom: 30px; font-size: 17px;text-align:right;">
        <strong>خوش آمدید</strong>
    </div>
    <div style="padding-bottom: 30px;text-align:right;">
        برای فعال کردن حساب کاربری خود، لطفاً روی دکمه زیر کلیک کنید تا آدرس ایمیل خود را تأیید کنید.
    </div>
    <div style="padding-bottom: 40px; text-align:center;">
        {!! $url !!}
    </div>
@endsection

@section('css')
    <style>
        .verify-account-btn {
            text-decoration:none;
            display:inline-block;
            text-align:center;
            padding:0.75575rem 1.3rem;
            font-size:0.925rem;
            line-height:1.5;
            border-radius:0.35rem;
            color:#ffffff;
            background-color:#50CD89;
            border:0px;
            margin-right:0.75rem!important;
            font-weight:600!important;
            outline:none!important;
            vertical-align:middle"
        }
    </style>
@endsection
