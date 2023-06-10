
@extends('admin.theme.master-email')
@section('title')
    بازیابی رمز عبور
@endsection
@section('main-content')
        <div style="padding-bottom: 30px; font-size: 17px;text-align:right;">
            <strong>خوش آمدید</strong>
        </div>
        <div style="padding-bottom: 30px;text-align:right;">
            برای بازیابی رمز عبور خود بر روی دکمه زیر کلیک کنید
        </div>
        <div style="padding-bottom: 40px; text-align:center;">
            {!! $url !!}
        </div>
        <div style="padding-bottom: 30px;text-align:right;line-height:35px;">
            این پیوند بازنشانی رمز عبور 60 دقیقه دیگر منقضی می شود. اگر بازنشانی رمز عبور را درخواست نکردید، هیچ اقدام دیگری لازم نیست.
        </div>
        <div style="border-bottom: 1px solid #eeeeee; margin: 15px 0"></div>
@endsection

@section('css')
    <style>
        .reset-password-btn {
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
@stop

