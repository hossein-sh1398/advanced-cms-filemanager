<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utility\SmsCode;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class TwoStepVerificationController extends Controller
{
    public function __construct($enableAuthorize = false)
    {
        parent::__construct($enableAuthorize);

        $this->middleware('auth');
    }

    /**
     * @return View|RedirectResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function form(): View|RedirectResponse
    {
        if (!session()->get('username')) {
            return to_route('auth.login');
        }

        return view('auth.pages.two-step-authentication-verify');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function verify(Request $request): RedirectResponse
    {
        if (! $username = session()->get('username')) {
            auth()->logout();

            return to_route('auth.login');
        }

        $request->validate(['code' => 'required']);

        $field = isMobile($username) ? 'mobile' : 'email';

        $user = User::where($field, $username)->first();

        if ($user && SmsCode::check($user, $request->get('code'))) {
           session()->put('twoStep', true);

            session()->forget('username');

            return to_route('admin.profile.index');
        }

        return back()->withInput()->withError('رمز وارد شده صحیح نمی باشد');
    }
}
