<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Models\Config;
use App\Utility\Table;
use Illuminate\Support\Str;
use App\Utility\FileManager;
use Illuminate\Http\Request;
use App\Utility\UploadFile\Logo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Utility\UploadFile\WaterMark;
use App\Utility\UploadFile\UserAvatar;
use Hekmatinasser\Verta\Facades\Verta;
use App\Http\Requests\Admin\ConfigRequest;
use App\Utility\UploadFile\Favicon;
use App\Utility\UploadFile\PromotionImage;
use Symfony\Component\HttpFoundation\Response;

class ConfigController extends Controller
{
    public function __construct($enableAuthorize = true)
    {
        parent::__construct($enableAuthorize);

        $this->table = new Table(['isFilter' => false, 'isCsv' => false, 'isExcel' => false, 'isPdf' => false,]);
    }

    /**
     * Undocumented function
     *
     * @return View
     */
    public function getMains()
    {
        $this->pageInfo->title = 'تنظیمات اصلی سایت';

        $configs = Config::whereType(true)->get();

        if (count($this->configParams()) !== count($configs)) {
            return $this->configGenerator();
        }

        $users = User::get()->filter(function($user) {
            if (auth()->user()->isAdministrator()) {
                return true;
            } else {
                return ! $user->isAdministrator();
            }
        })->pluck('name', 'mobile')->toArray();

        $configs = $configs->pluck('value', 'key')->toArray();

        $str = null;

        foreach ($configs['image_resize_value'] as $key => $value) {
            if ($key != 0) {
                $str .=',';
            }

            $str .= join('-', $value);
        }

        $configs['image_resize_value'] = $str;

        $configs['image_watermark_file'] = 'storage/' . $configs['image_watermark_file'];

        $configs['promotion_image'] = 'storage/' . $configs['promotion_image'];

        $configs['general_access_ip'] =  $configs['general_access_ip'];

        $configs['promotion_expire'] = verta($configs['promotion_expire'])->format('Y-m-d H:i:s');

        return view('admin.pages.configs.update', compact('configs', 'users'));
    }

    public function create()
    {
        $this->pageInfo->title = 'ایجاد تنظیم اختیاری';

        return view('admin.pages.configs.create');
    }

    public function store(Request $request)
    {
        try {
            $mainKey = $request->get('main_key');

            $configs = $request->get('value');

            if (! $configs || ! $mainKey) {
                return back()->withError('پر کردن مقدارها الزامی هست');
            }

           if (count($configs)) {
                $list = [];

                foreach ($configs as $value) {
                    if (empty($value['key']) && count($value['values']) == 1 && $value['values'][0]['value']) {
                        $list[] = $value['values'][0]['value'];
                    }
                }

                if (count($list) == 1) {
                    $config = Config::create([
                        'key' => $mainKey,
                        'value' => $list[0],
                        'num' => 1,
                        'type' => false,
                    ]);
                } else {
                    $list = [];

                    foreach ($configs as $value) {
                        if (empty($value['key']) && count($value['values']) == 1 && $value['values'][0]['value']) {
                            $list[] = $value['values'][0]['value'];
                        }
                    }

                    if (count($list) == count($configs)) {
                        $config = Config::create([
                            'key' => $mainKey,
                            'value' => $list,
                            'num' => 2,
                            'type' => false,
                        ]);
                    } else {
                        $list = [];

                        foreach ($configs as $value) {
                            if ($value['key'] && count($value['values'])) {
                                $v = [];
                                foreach ($value['values'] as $value2) {
                                    if ($value2['value']) {
                                        $v[] = $value2['value'];
                                    }
                                }
                                if (count($value['values']) == count($v)) {
                                    $list[][$value['key']] = $v;
                                } else {
                                    session()->flash('error', 'پرکردن تمامی فیلدها الزامیست');

                                    return back();
                                }
                            }
                        }

                        if (count($list) == count($configs)) {
                            $config = Config::create([
                                'key' => $mainKey,
                                'value' => $list,
                                'num' => 3,
                                'type' => false,
                            ]);
                        } else {
                            session()->flash('error', 'پرکردن تمامی فیلدها الزامیست');

                            return back();
                        }
                    }
                }
            }

            session()->flash('success', __('messages.store-success'));
        } catch (Exception $e) {
            session()->flash('error', __('messages.error'));
        }

        return redirectAfterSave($request->get('save_type'), $config);
    }

        /**
     *
     * @param Config $config
     * @return JsonResponse
     */
    public function destroy(Config $config): JsonResponse
    {
        try {
            if ($config->delete()) {
                return response()->json([
                    'status' => true,
                    'message' => 'ایتم انتخاب شده حذف گردید'
                ]);
            }

            throw new Exception('خطا در حذف، مجدد تلاش نمایید');
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

        /**
     * @param ConfigRequest $request
     * @return JsonResponse
     */
    public function deletes(ConfigRequest $request): JsonResponse
    {
        try {
            Config::whereIn('id', $request->input('ids'))->delete();

            return response()->json(['status' => true]);

        } catch (Exception $e) {
            return response()->json(['status' => false], Response::HTTP_BAD_REQUEST);
        }
    }

    public function edit(Config $config)
    {
        $this->pageInfo->title = 'ویرایش تنظیم اختیاری';

        return view('admin.pages.configs.create', compact('config'));
    }

    public function update(Request $request, Config $config)
    {
        try {
            $mainKey = $request->get('main_key');

            $configs = $request->get('value');

            if (! $configs || ! $mainKey) {
                return back()->withError('پر کردن مقدارها الزامی هست');
            }

           if (count($configs)) {
                $list = [];

                foreach ($configs as $value) {
                    if (empty($value['key']) && count($value['values']) == 1 && $value['values'][0]['value']) {
                        $list[] = $value['values'][0]['value'];
                    }
                }

                if (count($list) == 1) {
                    $config->update([
                        'key' => $mainKey,
                        'value' => $list[0],
                        'num' => 1,
                    ]);
                } else {
                    $list = [];

                    foreach ($configs as $value) {
                        if (empty($value['key']) && count($value['values']) == 1 && $value['values'][0]['value']) {
                            $list[] = $value['values'][0]['value'];
                        }
                    }

                    if (count($list) == count($configs)) {
                        $config->update([
                            'key' => $mainKey,
                            'value' => $list,
                            'num' => 2,
                        ]);
                    } else {
                        $list = [];

                        foreach ($configs as $value) {
                            if ($value['key'] && count($value['values'])) {
                                $v = [];
                                foreach ($value['values'] as $value2) {
                                    if ($value2['value']) {
                                        $v[] = $value2['value'];
                                    }
                                }
                                if (count($value['values']) == count($v)) {
                                    $list[][$value['key']] = $v;
                                } else {
                                    session()->flash('error', 'پرکردن تمامی فیلدها الزامیست');

                                    return back();
                                }
                            }
                        }

                        if (count($list) == count($configs)) {
                            $config->update([
                                'key' => $mainKey,
                                'value' => $list,
                                'num' => 3,
                            ]);
                        } else {
                            session()->flash('error', 'پرکردن تمامی فیلدها الزامیست');

                            return back();
                        }
                    }
                }
            }

            session()->flash('success', __('messages.store-success'));
        } catch (Exception $e) {
            session()->flash('error', __('messages.error'));
        }

        return redirectAfterSave($request->get('save_type'), $config);
    }

    public function index()
    {
        $configs = $this->table->get(Config::filters([['key' => 'type', 'op' => '=', 'value' => false]]), "*");

        $list = $configs;

        $table = $this->table->toArray();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'html'=> view('admin.pages.configs.data', compact('list'))->render(),
                'links' => view('admin.theme.elements.paginate-link', [
                    'models' => $configs,
                    'table' => $table,
                    ])->render(),
            ], 201);
        }

        $this->pageInfo->title = 'لیست تنظیمات اختیاری';

        $this->pageInfo->icon = 'fas fa-cog';

        $params = [
            'url' => route('admin.configs.index'),
            'model' => Str::replace('_', '.', (new Config())->getTable()),
            'table' => $table,
        ];

        return view('admin.pages.configs.index', $params);
    }

    public function mainUpdate(ConfigRequest $request)
    {
        $request['image_resize_value'] = Str::replace(['value', '[', ']', ':', '"', '}', '{'], '', $request->input('image_resize_value'));

        $request['image_resize_value'] = explode(',', $request['image_resize_value']);

        $sizeList = null;

        foreach ($request['image_resize_value'] as $value) {
            $sizeList[] =  explode('-', $value);
        }

        $request['image_resize_value'] = $sizeList;

        $request['sms_manager_mobile'] = $request->input('sms_manager_mobile', []);

        $request['promotion_expire'] = Verta::parse($request['promotion_expire'])->datetime()->format('Y-m-d H:i:s');

        $request['general_access_ip'] = Str::replace(['value', '[', ']', ':', '"', '}', '{'], '', $request->input('general_access_ip'));

        $request['general_access_register'] = $request->boolean('general_access_register');

        $request['general_offline'] = $request->boolean('general_offline');

        $request['sms_active'] = $request->boolean('sms_active');

        $request['promotion_active'] = $request->boolean('promotion_active');

        $request['promotion_loop'] = $request->boolean('promotion_loop');

        $request['social_share'] = $request->boolean('social_share');

        $request['image_optimize_active'] = $request->boolean('image_optimize_active');

        $request['image_watermark_active'] = $request->boolean('image_watermark_active');

        $request['image_resize_active'] = $request->boolean('image_resize_active');

        if ($request->input('promotion_loop') == 0) {
            session()->forget('promotion');
        }

        $socials = $request->input('socials') ?? [];

        $list = [];

        foreach ($socials as $value) {
            $list[] = [
                'name' => $value['socialName'],
                'url' => $value['socialUrl'],
            ];
        }

        $request['socials'] = $list;

        $configParams = $request->all();
        DB::transaction(function () use ($configParams, $request) {
            $defaultConfigFavicon = Config::where('key', 'image_favicon')->first();

            if ($request->hasFile('image_favicon')) {
                $fm = new FileManager;

                try {
                    $fm->uploadWay(new Favicon($request->file('image_favicon')));

                    if ($fm->upload()) {
                        $mediaFavicon = $defaultConfigFavicon->favicon;

                        if ($mediaFavicon) {
                            $mediaFavicon->delete();

                            FileManager::delete($mediaFavicon->url . '/' . $mediaFavicon->name);
                        }

                        $newAvatar = $defaultConfigFavicon->favicon()->create($fm->getFileInfo());

                        $configParams['image_favicon'] = $newAvatar->getUrl();
                    }
                } catch (Exception $e) {
                    session()->flash('error', 'خطا در آپلود تصویر فاوآیکون');

                    return redirect(route('admin.configs.index'));
                }
            }

            $defaultAvatar = Config::where('key', 'general_default_avatar')->first();

            if ($request->hasFile('general_default_avatar')) {
                $fm = new FileManager;
                try {
                    $fm->uploadWay(new UserAvatar($request->file('general_default_avatar')));

                    if ($fm->upload()) {
                        $defaultAvatarProfile = $defaultAvatar->defaultAvatarProfile;

                        if ($defaultAvatarProfile) {
                            $defaultAvatarProfile->delete();

                            FileManager::delete($defaultAvatarProfile->url . '/' . $defaultAvatarProfile->name);
                        }

                        $newAvatar = $defaultAvatar->defaultAvatarProfile()->create($fm->getFileInfo());

                        $configParams['general_default_avatar'] = $newAvatar->getUrl();
                    }
                } catch (Exception $e) {
                    session()->flash('error', 'خطا در آپلود تصویر پروفایل کاربری');

                    return redirect(route('admin.configs.index'));
                }

            }

            $promotionImageConfig = Config::where('key', 'promotion_image')->first();
            if ($request->hasFile('promotion_image')) {
                $fm = new FileManager;

                try {
                    $fm->uploadWay(new PromotionImage($request->file('promotion_image')));

                    if ($fm->upload()) {
                        $promotionImageBefore = $promotionImageConfig->promotionImage;

                        if ($promotionImageBefore) {
                            $promotionImageBefore->delete();

                            FileManager::delete($promotionImageBefore->url . '/' . $promotionImageBefore->name);
                        }

                        $media = $promotionImageConfig->promotionImage()->create($fm->getFileInfo());

                        $configParams['promotion_image'] =  $media->url . '/' . $media->name;
                    }
                } catch (Exception $e) {
                    session()->flash('error', 'خطا در آپلود تصویر پیشنهادی');

                    return redirect(route('admin.configs.index'));
                }
            }

            $defaultLogo = Config::where('key', 'general_default_logo')->first();
            if ($request->hasFile('general_default_logo')) {
                $fm = new FileManager;

                try {
                    $fm->uploadWay(new Logo($request->file('general_default_logo')));

                    if ($fm->upload()) {
                        $logo = $defaultLogo->logo;

                        if ($logo) {
                            $logo->delete();

                            FileManager::delete($logo->url . '/' . $logo->name);
                        }

                        $logo = $defaultLogo->logo()->create($fm->getFileInfo());

                        $configParams['general_default_logo'] = $logo->getUrl();
                    }
                } catch (Exception $e) {
                    session()->flash('error', 'خطا در آپلود تصویر لوگو');

                    return redirect(route('admin.configs.index'));
                }
            }

            $image_watermark_file = Config::where('key', 'image_watermark_file')->first();

            if ($request->hasFile('image_watermark_file')) {
                try {
                    if ($image_watermark_file->waterMark) {
                        $waterMark = $image_watermark_file->waterMark;

                        FileManager::delete($waterMark->url . '/' . $waterMark->name);

                        $waterMark->delete();
                    }

                    $fm = new FileManager;

                    $fm->uploadWay(new WaterMark($request->file('image_watermark_file')));

                    if ($fm->upload()) {

                        $waterMark = $image_watermark_file->waterMark()->create($fm->getFileInfo());

                        $configParams['image_watermark_file'] = $waterMark->url . '/' . $waterMark->name;
                    }
                } catch (Exception $e) {
                    session()->flash('error', 'خطا در آپلود تصویر واترمارک');

                    return redirect(route('admin.configs.index'));
                }
            }

            try {
                foreach ($configParams as $key => $value) {
                    $config = Config::where('key', $key)->first();
                    if ($config) {
                        $config->update([
                            'value' => $value
                        ]);
                    }
                }

                session()->flash('success', 'ویرایش با موفقیت انجام شد');
            } catch (Exception $e) {
                session()->flash('error', $e->getMessage());
            }
        });

        return redirect(route('admin.configs.mains'));
    }

    /**
     * Undocumented function
     *
     * @param boolean $stop
     * @return void
     */
    public function configGenerator($stop = false)
    {
        $data = $this->configParams();
        Config::query()->truncate();
        foreach ($data as $key => $value) {
            Config::create([
                'type' => 1,
                'key' => $key,
                'value' => $value
            ]);
        }
        if ($stop === false) {
            return $this->index();
        }
    }

    /**
     * if added parameter refresh config home page for generate config
     */
    public function configParams()
    {
        $configs = Config::all();
        return [
            'general_offline' => getConfigParam('general_offline', $configs) !== false ? getConfigParam('general_offline', $configs) : false,
            'general_offline_text' => getConfigParam('general_offline_text', $configs) !== false ? getConfigParam('general_offline_text', $configs) : 'سایت در حال بروز رسانی می باشد، از شکیبایی شما سپاس گذاریم',
            'general_access_ip' => getConfigParam('general_access_ip', $configs) !== false ? getConfigParam('general_access_ip', $configs) : null,
            'general_access_register' => getConfigParam('general_access_register', $configs) !== false ? getConfigParam('general_access_register', $configs) : true,
            'general_lang' => getConfigParam('general_lang', $configs) !== false ? getConfigParam('general_lang', $configs) : false,
            'general_default_avatar' => getConfigParam('general_default_avatar', $configs) !== false ? getConfigParam('general_default_avatar', $configs) : 'cdn/theme/custom/media/defaults/user-avatar.png',
            'general_default_logo' => getConfigParam('general_default_logo', $configs) !== false ? getConfigParam('general_default_logo', $configs) : 'cdn/theme/custom/media/defaults/logo.png',
            'general_app_version' => getConfigParam('general_app_version', $configs) !== false ? getConfigParam('general_app_version', $configs) : '1.0.0',
            'sms_active' => getConfigParam('sms_active', $configs) !== false ? getConfigParam('sms_active', $configs) : false,
            'sms_manager_mobile' => getConfigParam('sms_manager_mobile', $configs) !== false ? getConfigParam('sms_manager_mobile', $configs) : [],
            'sms_username' => getConfigParam('sms_username', $configs) !== false ? getConfigParam('sms_username', $configs) : null,
            'sms_password' => getConfigParam('sms_password', $configs) !== false ? getConfigParam('sms_password', $configs) : null,
            'sms_number' => getConfigParam('sms_number', $configs) !== false ? getConfigParam('sms_number', $configs) : null,
            'sms_url' => getConfigParam('sms_url', $configs) !== false ? getConfigParam('sms_url', $configs) : null,
            'seo_title' => getConfigParam('seo_title', $configs) !== false ? getConfigParam('seo_title', $configs) : null,
            'seo_google_webmaster' => getConfigParam('seo_google_webmaster', $configs) !== false ? getConfigParam('seo_google_webmaster', $configs) : null,
            'api_recaptcha_secret' => getConfigParam('api_recaptcha_secret', $configs) !== false ? getConfigParam('api_recaptcha_secret', $configs) : '6Lfm4xMTAAAAAHMtWx0uNmPEnSw7Fe0saVzcFRCE',
            'api_recaptcha_client' => getConfigParam('api_recaptcha_client', $configs) !== false ? getConfigParam('api_recaptcha_client', $configs) : '6Lfm4xMTAAAAAKaqs-npHO0DXVi4ih9qBIHTBA24',
            'api_crisp_id' => getConfigParam('api_crisp_id', $configs) !== false ? getConfigParam('api_crisp_id', $configs) : null,
            'api_raychat' => getConfigParam('api_raychat', $configs) !== false ? getConfigParam('api_raychat', $configs) : null,
            'promotion_active' => getConfigParam('promotion_active', $configs) !== false ? getConfigParam('promotion_active', $configs) : false,
            'promotion_type' => getConfigParam('promotion_type', $configs) !== false ? getConfigParam('promotion_type', $configs) : 'popup',
            'promotion_image' => getConfigParam('promotion_image', $configs) !== false ? getConfigParam('promotion_image', $configs) : 'cdn/theme/custom/media/defaults/promotion.png',
            'promotion_link' => getConfigParam('promotion_link', $configs) !== false ? getConfigParam('promotion_link', $configs) : null,
            'promotion_expire' => getConfigParam('promotion_expire', $configs) !== false ? getConfigParam('promotion_expire', $configs) : null,
            'promotion_loop' => getConfigParam('promotion_loop', $configs) !== false ? getConfigParam('promotion_loop', $configs) : false,
            'social_share' => getConfigParam('social_share', $configs) !== false ? getConfigParam('social_share', $configs) : null,
            'socials' => getConfigParam('socials', $configs) !== false ? getConfigParam('socials', $configs) : [],
            'image_optimize_active' => getConfigParam('image_optimize_active', $configs) !== false ? getConfigParam('image_optimize_active', $configs) : null,
            'image_optimize_value' => getConfigParam('image_optimize_value', $configs) !== false ? getConfigParam('image_optimize_value', $configs) : null,
            'image_watermark_active' => getConfigParam('image_watermark_active', $configs) !== false ? getConfigParam('image_watermark_active', $configs) : false,
            'image_watermark_file' => getConfigParam('image_watermark_file', $configs) !== false ? getConfigParam('image_watermark_file', $configs) : '',
            'image_favicon' => getConfigParam('image_favicon', $configs) !== false ? getConfigParam('image_favicon', $configs) : '',
            'image_watermark_opacity' => getConfigParam('image_watermark_opacity', $configs) !== false ? getConfigParam('image_watermark_opacity', $configs) : false,
            'image_watermark_percent' => getConfigParam('image_watermark_percent', $configs) !== false ? getConfigParam('image_watermark_percent', $configs) : false,
            'image_watermark_position' => getConfigParam('image_watermark_position', $configs) !== false ? getConfigParam('image_watermark_position', $configs) : false,
            'image_resize_active' => getConfigParam('image_resize_active', $configs) !== false ? getConfigParam('image_resize_active', $configs) : false,
            'image_resize_value' => getConfigParam('image_resize_value', $configs) !== false ? getConfigParam('image_resize_value', $configs) : [],
            'toc' => getConfigParam('toc', $configs) !== false ? getConfigParam('toc', $configs) : null,
            'image_article_category' => getConfigParam('image_article_category', $configs) !== false ? getConfigParam('image_article_category', $configs) : 'cdn/theme/custom/media/defaults/article-category.png',
        ];
    }
}
