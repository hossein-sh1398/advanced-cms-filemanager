@extends('admin.theme.master-email')
@section('title')
    رمز پویا
@endsection
@section('main-content')
    <div style="padding-bottom: 30px; font-size: 17px;direction: rtl;text-align:right;">
        <strong>خوش آمدید</strong>
    </div>
    <div style="padding-bottom: 30px;direction: rtl;text-align:right;">
        کاربر گرامی سلام
    </div>
    <div style="padding-bottom: 40px; text-align:center;">
        <span>{{ $code }}</span>
    </div>
@endsection
