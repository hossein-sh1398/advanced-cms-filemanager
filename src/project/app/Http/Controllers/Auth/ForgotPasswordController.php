<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\Config;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showForm()
    {
        $apiRecaptchaClient = Config::where('key', 'api_recaptcha_client')->value('value');

        return view('auth.pages.forget', compact('apiRecaptchaClient'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function send(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'email' => ['required', 'email', 'exists:users,email'],
           // 'g-recaptcha-response' => ['required', new RecaptchaRule()],
        ]);
        try {
            $status = Password::sendResetLink(
                ['email' => $request->email]
            );

            return $status === Password::RESET_LINK_SENT
                    ? back()->with(['success' => 'ارسال ایمیل انجام شد لطفا به اکانت ایمیل خود مراجعه نمایید'])
                    : back()->withInput()->with('error', 'ارسال ایمیل انجام نشد لطفا دوباره سعی نمایید');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'ارسال ایمیل انجام نشد لطفا دوباره سعی نمایید');
        }
    }

    /**
     * @param Request $request
     * @param $token
     * @return View
     */
    public function resetPassword(Request $request, $token): View
    {
        return view('auth.pages.reset', [
            'token' => $token,
            'email' => $request->query('email')
        ]);
    }

    /**
     * @param ResetPasswordRequest $request
     * @return RedirectResponse
     */
    public function update(ResetPasswordRequest $request): RedirectResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                   'password' => $password
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? to_route('auth.login')->with('success', 'رمز عبور با موفقیت بازیابی شد')
            : back()->with('error', 'بازیابی انجام نشد لطفا مجدد سعی نمایید');
    }
}
