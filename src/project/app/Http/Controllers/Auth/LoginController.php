<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use App\Utility\SmsCode;
use App\Rules\MobileRule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events\SMSReportEvent;
use App\Events\EmailReportEvent;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Config;
use App\Notifications\OtpEmailNotification;
use Psr\Container\NotFoundExceptionInterface;
use Illuminate\Validation\ValidationException;
use Psr\Container\ContainerExceptionInterface;

class LoginController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('guest')->except('logout');
    }

    /**
     * @return View
     */
    public function loginForm(): View
    {
        $configs = Config::whereIn('key', ['general_access_register', 'api_recaptcha_client'])->pluck('value', 'key')->toArray();

        $accessRegister = $configs['general_access_register'];

        $apiRecaptchaClient = $configs['api_recaptcha_client'];

        session()->forget('username');

        return view('auth.pages.login', compact('accessRegister', 'apiRecaptchaClient'));
    }

    /**
     * login with static password
     *
     * @param LoginRequest $request
     * @return Response|RedirectResponse
     */
    public function login(LoginRequest $request): Response|RedirectResponse
    {
        $data = $request->only('password');

        if (isMobile($request->get('username'))) {
            $data['mobile'] = $request->get('username');
        } else {
            $data['email'] = $request->get('username');
        }

        $result = Auth::attempt($data, $request->boolean('remember'));

        if ($result) {
            if (auth()->user()->roles->count()) {
                return to_route('admin.index');
            }

            return to_route('home');
        }

        session()->flash('error', 'نام کاربری یا رمز عبور اشتباه می باشد');

        return back()->withInput();
    }

    /**
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Auth()->logout();

        session()->forget('google2fa');

        session()->forget('twoStep');

        return to_route('auth.login');
    }

    /**
     * @return View
     */
    public function showFormOtp(): View
    {
        $configs = Config::whereIn('key', ['general_access_register', 'api_recaptcha_client'])->pluck('value', 'key')->toArray();

        $accessRegister = $configs['general_access_register'];

        $apiRecaptchaClient = $configs['api_recaptcha_client'];

        return view('auth.pages.otp', compact('accessRegister', 'apiRecaptchaClient'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse|ValidationException
     * @throws ValidationException
     */
    public function sendCode(Request $request): RedirectResponse|ValidationException
    {
        if (!isMobile($request->get('username'))) {
            $request->validate([
                'username' => ['required', 'email', 'exists:users,email'],
                //'g-recaptcha-response' => ['required', new RecaptchaRule()],
            ]);

            $user = User::where('email', $request->get('username'))->first();

            SmsCode::generate($user);

            $message = __('messages.send-otp-message', ['code' => $user->sms_code]);
            $emailSubject = __('messages.subject-email-otp');

            $user->notify(new OtpEmailNotification($message, $emailSubject));

            try {
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
            } catch (Exception $e) {
                return back()
                    ->withInput()->with('error', 'ارسال رمز پویا با خطا مواجه شد لطفا دوباره سعی نمایید');
            }

        } else {
            $this->validate($request, [
                'username' => ['required', 'exists:users,mobile', new MobileRule()],
                //'g-recaptcha-response' => ['required', new RecaptchaRule()],
            ]);

            $user = User::where('mobile', $request->username)->first();

            SmsCode::generate($user);

            $message = __('messages.send-otp-message', ['code' => $user->sms_code]);
            $emailSubject = __('messages.subject-email-otp');

            try {
                if (Config::where('key', 'sms_active')->value('value')) {
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

                if ($user->email) {
                    $user->notify(new OtpEmailNotification($message, $emailSubject));

                    event(new EmailReportEvent([
                        'model' => $user,
                        'moreData' => [
                            'content' => $message,
                            'email' => $user->email,
                        ],
                    ]));
                }

            } catch (Exception $e) {
                return back()
                    ->withInput()->with('error', 'ارسال رمز پویا با خطا مواجه شد لطفا دوباره سعی نمایید');
            }
        }

        session()->put('username', $request->username);

        session()->flash('success', " کد ارسال شده به  $request->username را وارد نمایید");

        return to_route('auth.login.verify.form');
    }

    /**
     * @return RedirectResponse|View
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function showVerifyForm(): RedirectResponse|View
    {
        if (! session()->get('username')) {
            return to_route('auth.login.form.otp');
        }

        return view('auth.pages.verify-otp');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function verify(Request $request):RedirectResponse
    {
       if (! $username = session()->get('username')) {
            return to_route('login.form.otp');
       }

       $this->validate($request, ['code' => 'required']);

       $field = !isMobile($username) ? 'email' : 'mobile';

        $user = User::where($field, $username)->first();

        if (SmsCode::check($user, $request->get('code'))) {
            Auth::login($user);

            session()->forget('username');

            if ($user->roles->count()) {
                return to_route('admin.index');
            }

            return to_route('home');
        } else {
            return to_route('login.verify.form')->with('error',  'رمز پویا صحیح نمی باشد');
        }
    }
}
