<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Config;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showForm()
    {
        $configs = Config::whereIn('key', ['general_access_register', 'api_recaptcha_client', 'toc'])->pluck('value', 'key')->toArray();

        $apiRecaptchaClient = $configs['api_recaptcha_client'];

        $toc = $configs['toc'];

        if ($configs['general_access_register']) {
            return view('auth.pages.register', compact('toc', 'apiRecaptchaClient'));
        }

        return to_route('home')->with('error', 'کاربر گرامی در حال حاضر امکان ثبت نام در سایت بسته شده');
    }

    /**
     * @param RegisterRequest $request
     * @return RedirectResponse|string
     */
    public function register(RegisterRequest $request): RedirectResponse|string
    {
        if (Config::where('key', 'general_access_register')->first()->value) {
            try {
                $user = UserRepository::register($request->validated());

                auth()->login($user);

                return to_route('auth.verification.notice');
            } catch (Exception $e) {
                return to_route('auth.register')
                    ->withInput()
                    ->withErrors( 'خطایی در ثبت اطلاعات به وجود آمده');
            }
            return to_route('auth.login')->withSuccess('کاربر مورد نظر با موفقیت ایجاد شد');
        }

        return to_route('auth.register')->withSuccess('کاربر گرامی فعلا امکان ثبت نام در سایت مقدور نمی باشد');
    }
}
