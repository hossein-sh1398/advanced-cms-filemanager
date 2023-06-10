<?php
$name = env('APP_NAME');

return [
    'sms' => [
        'success' => 'ارسال رمز پویا با موفقیت انجام شد',
        'error' => 'رمز پویا ارسال نشد لطفا دوباره سعی نمایید',
    ],
    'edit-success' => 'عمل ویرایش با موفقیت انجام شد',
    'store-success' => 'عمل ثبت با موفقیت انجام شد',
    'error' => 'به نظر خطایی پیش آمده، لطفا مجدد تلاش نمایید.',
    'newsLetters' => [
        'subscribe' => 'درخواست اشتراک شما با موفقیت ثبت شد'
    ],
    'verify-email' => 'ایمیل شما با موفقیت تایید گردید',

    'send-otp-message' => "رمز پویاِی :code از طرف سایت $name",
    'subject-email-otp' => 'رمز پویا',
    'verify-account-message' => "لینک تایید حساب شما : <a href=':url' class='verify-account-btn' target='_blank'>تایید ایمیل</a>",
    'subject-email-email-subject' => 'لینک تایید حساب کاربری',
    'reset-password-link' => "لینک بازیابی پسورد : <a href=':url' class='reset-password-btn' target='_blank'>کلیک کنید</a>",
    'reset-password-email-subject' => 'لینک بازیابی پسورد',
];
