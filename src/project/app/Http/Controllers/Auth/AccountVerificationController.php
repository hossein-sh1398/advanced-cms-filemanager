<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\Config;
use App\Utility\SmsCode;
use Illuminate\Http\Request;
use App\Events\SMSReportEvent;
use App\Events\EmailReportEvent;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Notifications\OtpEmailNotification;

class AccountVerificationController extends Controller
{
    /**
     * @return View
     */
    public function notice(): View
    {
        return view('auth.pages.verify-account');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate(['sms_code' => 'required']);

        $user = auth()->user();

        if (SmsCode::check($user, $request->get('sms_code'))) {
            $user->account_verified_at = now();

            $user->save();

            return to_route('home')->with('success', 'حساب کاربری شما تایید شد');
        }

        return back()->with('error', 'رمز پویا صحیح نمی باشد');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function reSendCode(Request $request): RedirectResponse
    {
        try {
            $user = $request->user();

            SmsCode::generate($user);

            $message = __('messages.send-otp-message', ['code' => $user->sms_code]);
            $emailSubject = __('messages.subject-email-otp');

            $user->notify(new OtpEmailNotification($message, $emailSubject));

            event(new EmailReportEvent([
                'model' => $user,
                'moreData' => [
                    'content' => $message,
                    'email' => $user->email,
                ],
            ]));

            if (Config::where('key', 'sms_active')->value('value')) {
                if ($user->mobile) {
                    $res = $user->sendSms($message, $user->mobile);

                    event(new SMSReportEvent([
                        'model' => $user,
                        'moreData' => [
                            'content' => $message,
                            'mobile' => $user->mobile,
                        ],
                        'delivery' => $res,
                    ]));
                }
            }

            return back()->with('success', 'کد تایید حساب به موبایل و ایمیل شما ارسال شد');
        } catch (Exception $e) {
            return back()->with('error', 'خطا در ارسال کد ,لطفا مجدد سعی نمایید.');
        }
    }
}
