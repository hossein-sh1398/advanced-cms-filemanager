<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class MenuShareProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::share('adminMenus', $this->getAdminMenus());
    }

    private function getAdminMenus(): array
    {
        return [
            [
                'permissions' => ['admin.index'],
                'title' => 'صفحه اصلی',
                'active' => 'admin',
                'icon' => 'home',
                'route' => '/admin',
                'sub' => []
            ],
            [
                'permissions' => ['admin.users.index', 'admin.roles.index', 'admin.permissions.index', 'admin.roles.access'],
                'title' => 'کاربران',
                'active' => '',
                'icon' => 'home',
                'route' => '/admin/users',
                'sub' => [
                    [
                        'permissions' => ['admin.users.index'],
                        'title' => 'مدیریت کاربران',
                        'active' => 'admin/users',
                        'icon' => 'home',
                        'route' => '/admin/users',
                        'sub' => []
                    ],
                    [
                        'permissions' => ['admin.roles.index'],
                        'title' => 'مدیریت دسته بندی کاربران',
                        'active' => 'admin/roles',
                        'icon' => 'home',
                        'route' => '/admin/roles',
                        'sub' => []
                    ],
                    [
                        'permissions' => ['admin.permissions.index',],
                        'title' => 'مدیریت دسترسی',
                        'active' => 'admin/permissions',
                        'icon' => 'home',
                        'route' => '/admin/permissions',
                        'sub' => []
                    ],
                    [
                        'permissions' => ['admin.roles.access'],
                        'title' => 'مدیریت سطوح دسترسی',
                        'active' => 'admin/access/roles',
                        'icon' => 'home',
                        'route' => '/admin/access/roles',
                        'sub' => []
                    ]
                ]
            ],
            [
                'permissions' => ['admin.articles.index', 'admin.article.categories.index', 'admin.tags.index', 'admin.comments.index'],
                'title' => 'مقالات',
                'active' => '',
                'icon' => 'home',
                'route' => '/admin/articles',
                'sub' => [
                    [
                        'permissions' => ['admin.articles.index'],
                        'title' => 'مدیریت مقالات',
                        'active' => 'admin/articles',
                        'icon' => 'home',
                        'route' => '/admin/articles',
                        'sub' => []
                    ],
                    [
                        'permissions' => ['admin.article.categories.index'],
                        'title' => 'دسته بندی مقالات',
                        'active' => 'admin/article/categories',
                        'icon' => 'home',
                        'route' => '/admin/article/categories',
                        'sub' => []
                    ],
                    [
                        'permissions' => ['admin.tags.index'],
                        'title' => 'کلمات کلیدی',
                        'active' => 'admin/tags',
                        'icon' => 'home',
                        'route' => '/admin/tags',
                        'sub' => []
                    ],
                    [
                        'permissions' => ['admin.comments.index'],
                        'title' => 'مدریت نظرات',
                        'active' => 'admin/comments',
                        'icon' => 'home',
                        'route' => '/admin/comments',
                        'sub' => []
                    ]
                ]
            ],
            [
                'permissions' => ['admin.messages.index'],
                'title' => 'بخش اعلانات',
                'active' => '',
                'icon' => 'home',
                'route' => 'admin/messages',
                'sub' => [
                    [
                        'permissions' => ['admin.messages.index'],
                        'title' => 'مدیریت پیام ها',
                        'active' => 'admin/messages',
                        'icon' => 'home',
                        'route' => '/admin/messages',
                        'sub' => []
                    ],
                ]
            ],
            [
                'permissions' => ['admin.news.letters.index'],
                'title' => 'بخش خبرنامه',
                'active' => '',
                'icon' => 'home',
                'route' => 'admin/news/letters',
                'sub' => [
                    [
                        'permissions' => ['admin.news.letters.index'],
                        'title' => 'اعضا خبرنامه',
                        'active' => 'admin/news/letters',
                        'icon' => 'home',
                        'route' => '/admin/news/letters',
                        'sub' => []
                    ]
                ]
            ],
            [
                'permissions' => ['admin.configs.index', 'admin.configs.mains', 'admin.profile.index'],
                'title' => 'بخش تنظیمات',
                'active' => '',
                'icon' => 'home',
                'route' => 'admin/configs',
                'sub' => [
                    [
                        'permissions' => ['admin.configs.mains'],
                        'title' => 'تنظیمات اصلی',
                        'active' => 'admin/primitive/configs',
                        'icon' => 'home',
                        'route' => '/admin/primitive/configs',
                        'sub' => []
                    ],
                    [
                        'permissions' => ['admin.configs.index'],
                        'title' => 'تنظیمات اختیاری',
                        'active' => 'admin/configs',
                        'icon' => 'home',
                        'route' => '/admin/configs',
                        'sub' => []
                    ],
                    // [
                    //     'title' => 'تنظیمات سئو',
                    //     'active' => 'admin/configs',
                    //     'icon' => 'home',
                    //     'route' => '/admin/configs',
                    //     'sub' => []
                    // ],
                    [
                        'permissions' => ['admin.profile.index'],
                        'title' => 'ویرایش پروفایل کاربری',
                        'active' => 'admin/profile/index',
                        'icon' => 'home',
                        'route' => '/admin/profile/index',
                        'sub' => []
                    ],
                ]
            ],
            [
                'permissions' => ['admin.reports.email', 'admin.reports.sms', 'admin.logs.viewer'],
                'title' => 'بخش گزارشات',
                'active' => '',
                'icon' => 'home',
                'route' => 'admin/reprots',
                'sub' => [
                    [
                        'permissions' => ['admin.reports.email',],
                        'title' => 'گزارش ایمیل ها',
                        'active' => 'admin/reports/email',
                        'icon' => 'home',
                        'route' => '/admin/reports/email?report_type=email',
                        'sub' => []
                    ],
                    [
                        'permissions' => ['admin.reports.sms'],
                        'title' => 'گزارش پیامک',
                        'active' => 'admin/reports/sms',
                        'icon' => 'home',
                        'route' => '/admin/reports/sms?report_type=sms',
                        'sub' => []
                    ],
                    [
                        'permissions' => ['admin.logs.viewer'],
                        'title' => 'گزارش خطاها',
                        'active' => 'admin/reports/logs/viewer',
                        'icon' => 'home',
                        'route' => '/admin/reports/logs/viewer',
                        'sub' => []
                    ],
                ]
            ],
            [
                'permissions' => ['admin.histories.index'],
                'title' => 'اقدامات',
                'active' => '',
                'icon' => 'home',
                'route' => 'admin/histories',
                'sub' => [
                    [
                        'permissions' => ['admin.histories.index'],
                        'title' => 'اقدامات کاربر',
                        'active' => 'admin/histories',
                        'icon' => 'home',
                        'route' => '/admin/histories',
                        'sub' => []
                    ],
                ]
            ],
            [
                'permissions' => ['admin.file.manager.index'],
                'title' => 'بخش رسانه',
                'active' => '',
                'icon' => 'home',
                'route' => 'admin/file/manager',
                'sub' => [
                    [
                        'permissions' => ['admin.file.manager.index'],
                        'title' => 'لیست فایل ها و فولدرها',
                        'active' => 'admin/file/manager',
                        'icon' => 'home',
                        'route' => '/admin/file/manager',
                        'sub' => []
                    ],
                ]
            ],
        ];
    }
}
