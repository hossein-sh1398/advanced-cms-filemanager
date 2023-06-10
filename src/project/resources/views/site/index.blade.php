<html>
    <head>
        <style>
            body{
                text-align: right;
                direction: rtl;
            }
        </style>
    </head>
    <body>
        <h3>صفحه اول سایت</h3>
        <a href="{{ route('admin.index') }}">پنل ادمین</a>
        <br>
        <a href="{{ route('auth.logout') }}">خروج از حساب کاربری</a>
    </body>
</html>
