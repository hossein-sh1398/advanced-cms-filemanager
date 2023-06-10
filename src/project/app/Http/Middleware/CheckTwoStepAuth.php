<?php

namespace App\Http\Middleware;

use Closure;
use App\Utility\SmsCode;
use App\Enums\TwoStepType;
use Illuminate\Http\Request;
use App\Events\SMSReportEvent;
use App\Events\EmailReportEvent;
use App\Notifications\OtpEmailNotification;

class CheckTwoStepAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if ($user->twoStepStatus()) {
            if ($user->twoStepType(TwoStepType::Email) || $user->twoStepType(TwoStepType::Mobile)) {
                if (session()->has('twoStep')) {
                    return $next($request);
                } else {
                    return $this->twoStepActions($user);
                }
            }
        }

        return $next($request);
    }

    /**
     * @param $user
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function twoStepActions($user)
    {
        SmsCode::generate($user);

        $message = __('messages.send-otp-message', ['code' => $user->sms_code]);

        if ($user->twoStepType(TwoStepType::Email)) {
            try {
                $emailSubject = __('messages.subject-email-otp');

                $user->notify(new OtpEmailNotification($message, $emailSubject));

                event(new EmailReportEvent([
                    'model' => $user,
                    'moreData' => [
                        'content' => $message,
                        'email' => $user->email,
                    ],
                ]));

                session()->put('username', $user->email);

                session()->flash('success', "رمز پویا به ایمیل $user->email ارسال شد");

                return to_route('auth.two.step.authentication.form');
            } catch (\Exception $e) {
                auth()->logout();

                session()->forget('username');

                session()->flash('error', 'ارسال رمز پویا با خطا مواجه شد، لطفا مجدد سعی نمایید');

                return to_route('auth.login');
            }
        } else if ($user->twoStepType(TwoStepType::Mobile)) {
            try {
                $res = $user->sendSms($message, $user->mobile);

                event(new SMSReportEvent([
                    'model' => $user,
                    'moreData' => [
                        'content' => $message,
                        'delivery' => $res,
                        'mobile' => $user->mobile,
                    ]
                ]));

                session()->put('username', $user->mobile);

                session()->flash('success', "رمز پویا به شماره موبایل $user->mobile ارسال شد");

                return to_route('auth.two.step.authentication.form');
            } catch (\Exception $e) {
                auth()->logout();

                session()->forget('username');

                session()->flash('error', 'ارسال رمز پویا با خطا مواجه شد، لطفا مجدد سعی نمایید');

                return to_route('auth.login');
            }
        }
    }
}
