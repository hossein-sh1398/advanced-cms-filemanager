<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Config;
use App\Utility\SmsCode;
use App\Enums\TwoStepType;
use App\Utility\FileManager;
use Illuminate\Http\Request;
use App\Events\SMSReportEvent;
use App\Events\EmailReportEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Utility\UploadFile\UserAvatar;
use PragmaRX\Google2FAQRCode\Google2FA;
use App\Http\Requests\Admin\ProfileRequest;
use App\Notifications\OtpEmailNotification;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;

class ProfileController extends Controller
{
    public function __construct($enableAuthorize = true)
    {
        parent::__construct($enableAuthorize);

        $this->pageInfo->title = 'پروفایل کاربر';
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $user = auth()->user();

        $smsActive = Config::where('key', 'sms_active')->value('value');

        return view('admin.pages.profile.index', compact('user', 'smsActive'));
    }

    /**
     * update profile
     *
     * @param ProfileRequest $request
     * @return RedirectResponse
     * @throws ValidationException
     * @throws IncompatibleWithGoogleAuthenticatorException
     * @throws InvalidCharactersException
     * @throws SecretKeyTooShortException
     */
    public function update(ProfileRequest $request): RedirectResponse
    {
        $user = \auth()->user();

        $user->name = $request->get('name');

        $user->email = $request->get('email');

        $user->mobile = $request->get('mobile');

        if ($request->filled('password')) {
            $user->password = $request->get('password');
        }
        try {
            DB::beginTransaction();

            if ($request->hasFile('avatar')) {
                if ($user->profileAvatar) {
                    FileManager::delete($user->profileAvatar->url.'/'.$user->profileAvatar->name);

                    $user->profileAvatar()->delete();
                }

                $fileManager = new FileManager();

                $fileManager->uploadWay(new UserAvatar($request->file('avatar')));

                if ($fileManager->upload()) {
                    $user->profileAvatar()->create($fileManager->getFileInfo());
                }
            }

            $user->save();

            session()->flash('success', __('messages.edit-success'));

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            session()->flash('error', 'خطا در ثبت، لطفا مجدد تلاش نمایید');
        }

        return to_route('admin.profile.index');
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return HttpResponse
     */
    public function verifyEmailCode(Request $request): HttpResponse
    {
        try {
            $user = auth()->user();

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

            if ($request->ajax()) {
                return response(['status' => true], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response([
                    'status' => false,
                    'message' => 'خطا در ارسال کد، لطفا مجدد تلاش نمایید',
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return HttpResponse
     */
    public function verifyMobileCode(Request $request): HttpResponse
    {
        try {
            if (Config::where('key', 'sms_active')->value('value')) {
                throw new Exception('سرویس پیامک سایت فعال نمی باشد');
            }

            $user = auth()->user();

            SmsCode::generate($user);

            $message = __('messages.send-otp-message', ['code' => $user->sms_code]);

            $res = $user->sendSMS($message, $user->mobile);

            event(new SMSReportEvent([
                'model' => $user,
                'moreData' => [
                    'content' => $message,
                    'mobile' => $user->mobile,
                ],
                'delivery' => $res,
            ]));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response([
                    'status' => false,
                    'message' => $e->getMessage(),
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        if ($request->ajax()) {
            return response(['status' => true], Response::HTTP_OK);
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return HttpResponse
     */
    public function verifyEmail(Request $request): HttpResponse
    {
        try {
            $user = auth()->user();

            if (SmsCode::check($user, $request->get('code'))) {
                $user->email_verified_at = now();

                $user->two_step_status = true;

                $user->two_step_type = TwoStepType::Email;

                $user->save();

                session()->put('twoStep', true);
            } else {
                throw new Exception('کد تایید صحیح نمی باشد');
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response([
                    'status' => false,
                    'message' => $e->getMessage()
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        if ($request->ajax()) {
            return response(['status' => true], Response::HTTP_OK);
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return HttpResponse
     */
    public function verifyMobile(Request $request): HttpResponse
    {
        try {
            $user = auth()->user();

            if (SmsCode::check($user, $request->get('code'))) {
                $user->mobile_verified_at = now();

                $user->two_step_status = true;

                $user->two_step_type = TwoStepType::Mobile;

                $user->save();

                session()->put('twoStep', true);
            } else {
                throw new Exception('کد تایید صحیح نمی باشد');
            }

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response([
                    'status' => false,
                    'message' => $e->getMessage()
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        if ($request->ajax()) {
            return response(['status' => true], Response::HTTP_OK);
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return HttpResponse
     */
    public function verifyGoogle(Request $request): HttpResponse
    {
        try {
            $user = auth()->user();

            if ($user->google2fa_secret && $code = $request->get('code')) {
                $valid = (new Google2FA())->verifyKey(
                    $user->google2fa_secret,
                    $code
                );

                if ($valid) {
                    $user->two_step_type = TwoStepType::Google;

                    $user->two_step_status = true;

                    $user->save();

                    session()->put('twoStep', true);

                    if ($request->ajax()) {
                        return response(['status' => true], Response::HTTP_OK);
                    }
                } else {
                    throw new Exception('کد تایید صحیح نمی باشد');
                }
            } else {
                throw new Exception('لطفا کد تایید را وارد نمایید');
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response([
                    'status' => false,
                    'message' => $e->getMessage()
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return HttpResponse
     */
    public function generateQRCode(Request $request): HttpResponse
    {
        $user = auth()->user();

        try {
            $google2fa = new Google2FA();

            $user->google2fa_secret = $google2fa->generateSecretKey();

           $user->save();

            $google2fa_url = $google2fa->getQRCodeInline(
                env('APP_NAME'),
                $user->email,
                $user->google2fa_secret
            );

            $secret_key = $user->google2fa_secret;
        } catch (\Exception) {
            if ($request->ajax()) {
                return response([ 'status' => false, ], Response::HTTP_BAD_REQUEST);
            }
        }

        if ($request->ajax()) {
            return response([
                'status' => true,
                'google2fa_url' => nl2br($google2fa_url),
                'secret_key' => $secret_key,
            ], Response::HTTP_OK);
        }
    }

    public function activeTwoStep(Request $request)
    {
        try {
            $user = auth()->user();

            $user->two_step_status = $request->boolean('status');

            $user->save();

            if ($request->boolean('status')) {
                session()->put('twoStep', true);
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response([
                    'status' => false,
                    'message' => $e->getMessage(),
                ], Response::HTTP_OK);
            }
        }

        $message = null;
        if ($user->two_step_type) {
            if ($request->boolean('status')) {
                $message = 'احراز هویت دو مرحله ای فعال شد';
            } else {
                $message = 'احراز هویت دو مرحله ای غیر فعال شد';
            }
        }

        if ($request->ajax()) {
            return response([
                'status' => true,
                'message' =>  $message,
            ], Response::HTTP_OK);
        }
    }

    public function activeUnActiveTwoStepType(Request $request)
    {
        try {
            $user = auth()->user();

            $user->two_step_type = $request->query('type');

            $user->save();

            session()->flash('success', 'فعال سازی با موفقیت انجام شد');
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response([
                    'status' => true,
                    'message' => $e->getMessage(),
                ], Response::HTTP_OK);
            }
        }

       return to_route('admin.profile.index');
    }
}
