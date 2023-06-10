<?php

use App\Models\User;
use App\Http\Middleware\Maintenance;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckTwoStepAuth;
use App\Http\Middleware\HistoryMiddleware;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\LikeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Middleware\RedirectIsActiveAccount;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Site\SubscribeController;
use App\Http\Controllers\Admin\NewsLetterController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\FileManagerController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\ArticleCategoryController;
use App\Http\Controllers\Auth\AccountVerificationController;
use App\Http\Controllers\Auth\TwoStepVerificationController;

Route::middleware(Maintenance::class)->group(function () {
    Route::get('/', function () {
        return view('site.index');
    })->name('home');

                      //   ----------------- Authenticate Routes --------------------------
    Route::as('auth.')->group(function () {
        Route::prefix('register')->controller(RegisterController::class)->group(function () {
            Route::get('/', 'showForm')->name('register.form');
            Route::post('/', 'register')->name('register');
        });

        // Authentication Routes
        Route::prefix('login')->controller(LoginController::class)->group(function () {
            // auth with static password
            Route::get('/', 'loginForm')->name('login.form');
            Route::post('/', 'login')->name('login');

            // auth with one time password
            Route::prefix('otp')->group(function () {
                Route::get('/', 'showFormOtp')->name('login.form.otp');
                Route::post('/', 'sendCode')->name('login.send.code');
                Route::prefix('verify')->group(function () {
                    Route::get('/', 'showVerifyForm')->name('login.verify.form');
                    Route::post('/', 'verify')->name('login.verify');
                });
            });
        });

        //Two Step Authentication Routes
        Route::prefix('two/step/authentication')->controller(TwoStepVerificationController::class)->group(function () {
            Route::get('/', 'form')->name('two.step.authentication.form');
            Route::post('/', 'verify')->name('two.step.authentication.verify');
        });

        //forgot-password
        Route::controller(ForgotPasswordController::class)->group(function () {
            Route::prefix('forgot')->group(function () {
                Route::get('/', 'showForm')->name('forgot.password.form');
                Route::post('/', 'send')->name('forgot.link');
            });
            Route::post('reset/password', 'update')->name('password.update');
        });

        // // Routes that require authentication
        Route::middleware('auth')->group(function () {
            Route::get('logout', [LoginController::class, 'logout'])->name('logout');

            // Verification Email Routes
            Route::prefix('account/verify')->middleware(RedirectIsActiveAccount::class)->controller(AccountVerificationController::class)->group(function () {
                Route::get('/notice', 'notice')->name('verification.notice');
                Route::get('/resend/code', 'reSendCode')->name('account.verify.re.send.code');
                Route::post('/finally', 'verify')->name('verify.account');
            });
        });
    });

    Route::prefix('reset/password')->controller(ForgotPasswordController::class)->group(function () {
        Route::get('/{token}', 'resetPassword')->name('password.reset');
    });

                        //  ----------------- End Authenticate Routes ---------------------

    Route::get('news/letters/verify/email/{newsLetter}', [SubscribeController::class, 'verifyEmail'])->name('news.letters.verify.email');
});


                            //   ------------------ Admin Routes -------------------

Route::prefix('admin')->middleware([
    'auth', 'active.account', HistoryMiddleware::class, CheckTwoStepAuth::class
    ])->as('admin.')->group(function () {
    // Start Dashboard routes
    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });

    // Start Profile routes
    Route::prefix('/profile')->as('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/index', 'index')->name('index');
        Route::post('verify/emailCode', 'verifyEmailCode')->name('verify.email.code');
        Route::post('verify/email', 'verifyEmail')->name('verify.email');
        Route::post('verify/mobile/code', 'verifyMobileCode')->name('verify.mobile.code');
        Route::post('verify/mobile', 'verifyMobile')->name('verify.mobile');
        Route::post('generate/QRCode', 'generateQRCode')->name('generate.QRCode');
        Route::post('verify/google', 'verifyGoogle')->name('verify.google');
        Route::post('update', 'update')->name('update');
        Route::post('active/two/step', 'activeTwoStep')->name('active.two.step');
        Route::get('active/un/active/two/step/type', 'activeUnActiveTwoStepType')->name('active.unactive.two.step.type');
    });
    // End Profile Routes

    //Start Users Routes
    Route::prefix('/users')->as('users.')->controller(UserController::class)->group(function () {
        Route::get('/get', 'getUsers')->name('get');
        Route::delete('/multiple/destroy', 'multipleDestroy')->name('multiple.destroy');
        Route::post('/change/multiple/status', 'changeMultipleStatus')->name('change.multiple.status');
        Route::get('/{user}/change/status', 'changeStatus')->name('change.status');
        Route::get('/export', 'export')->name('export');
    });
    Route::resource('users', UserController::class)->except('show');
    // End Users Routes

    //Start Roles Routes
    Route::prefix('/roles')->controller(RoleController::class)->as('roles.')->group(function () {
        Route::get('/ajax', 'roles')->name('ajax');
        Route::delete('/multi/destroy', 'multipleDestroy')->name('multiple.destroy');
        Route::post('/add/permissions/to/role', 'addPermissionsToRole')->name('add.permissions.to.role');
        Route::get('/export', 'export')->name('export');

    });
    Route::resource('roles', RoleController::class)->except('show');
    Route::get('/access/roles', [RoleController::class, 'access'])->name('roles.access');
    // End Roles Routes

    //Start Permissions Routes
    Route::prefix('/permissions')->controller(PermissionController::class)->as('permissions.')->group(function () {
        Route::get('/sync', 'sync')->name('sync');
        Route::get('/ajax', 'permissions')->name('ajax');
        Route::delete('/multiple/destroy', 'multipleDestroy')->name('multiple.destroy');
        Route::get('/export', 'export')->name('export');
    });
    Route::resource('permissions', PermissionController::class)->except('show');
    // End Permissions Routes

    //Start article_category Routes
    Route::prefix('/article/categories')->controller(ArticleCategoryController::class)->as('article.categories.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/ajax', 'getArticleCategories')->name('ajax');
        Route::delete('/{articleCategory}', 'destroy')->name('destroy');
        Route::get('/{articleCategory}/edit', 'edit')->name('edit');
        Route::patch('/{articleCategory}', 'update')->name('update');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::delete('/multiple/destroy', 'multipleDestroy')->name('multiple.destroy');
        Route::get('/export', 'export')->name('export');
        Route::get('/status/{articleCategory}', 'editStatus')->name('status');
    });

    // End article_category Routes

    //Start articles Routes
    Route::prefix('/articles')->controller(ArticleController::class)->as('articles.')->group(function () {
        Route::get('/ajax', 'getArticles')->name('ajax');
        Route::delete('/multiple/destroy', 'multipleDestroy')->name('multiple.destroy');
        Route::post('/video/upload', 'uploadVideos')->name('upload.videos');
        Route::post('/photos/upload', 'uploadPhotos')->name('upload.photos');
        Route::delete('/media/{media}/delete', 'deleteMedia')->name('delete.media');
        Route::post('/ckeditor/upload/{id?}', 'ck_upload')->name('ck.upload');
        Route::delete('/gallery/permanently/delete', 'deletePermanentlyGallery')->name('delete.permanently.gallery');
        Route::delete('/video/permanently/delete', 'deletePermanentlyVideo')->name('delete.permanently.video');
        Route::get('/export', 'export')->name('export');
        Route::get('/status/{article}', 'editStatus')->name('status');
        Route::get('/special/{article}', 'editSpecial')->name('special');
    });
    Route::resource('articles', ArticleController::class)->except('show');
    // End article_category Routes

    //Start Tags Routes
    Route::prefix('/tags')->controller(TagController::class)->as('tags.')->group(function () {
        Route::get('/ajax', 'tags')->name('ajax');
        Route::delete('/multiple/destroy', 'multipleDestroy')->name('multiple.destroy');
        Route::get('/export', 'export')->name('export');
    });
    Route::resource('tags', TagController::class)->except('show');
    // End Tags Routes

    //Start Comments Routes
    Route::prefix('/comments')->controller(CommentController::class)->as('comments.')->group(function () {
        Route::get('/ajax', 'comments')->name('ajax');
        Route::put('/{comment}/verify', 'verify')->name('verify');
        Route::put('/{comment}/read/status', 'editStatus')->name('read.status');
        Route::delete('/multiple/destroy', 'multipleDestroy')->name('multiple.destroy');
        Route::get('/export', 'export')->name('export');
    });
    Route::resource('comments', CommentController::class);
    // End Comments Routes

    //Start Messages Routes
    Route::prefix('/messages')->controller(MessageController::class)->as('messages.')->group(function () {
        Route::get('/ajax', 'messages')->name('ajax');
        Route::delete('/multiple/destroy', 'multipleDestroy')->name('multiple.destroy');
        Route::get('/export', 'export')->name('export');
        Route::put('/{message}/edit/status', 'editStatus')->name('edit.status');
        Route::put('/{id}/restore', 'restore')->name('restore');
    });
    Route::resource('messages', MessageController::class)->except('edit', 'update');
    // End Message Routes

    //Start Likes Routes
    Route::controller(LikeController::class)->as('likes.')->group(function () {
        Route::post('/like', 'like')->name('like');
        Route::post('/unlike', 'unlike')->name('unlike');
    });
    // End Likes Routes

    //Start NewsLetters Routes
    Route::prefix('/news/letters')->controller(NewsLetterController::class)->as('news.letters.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/ajax', 'newsLetters')->name('ajax');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{newsLetter}/edit', 'edit')->name('edit');
        Route::patch('/{newsLetter}', 'update')->name('update');
        Route::delete('/{newsLetter}', 'destroy')->name('destroy');
        Route::delete('/multiple/destroy', 'multipleDestroy')->name('multiple.destroy');
        Route::get('/export', 'export')->name('export');
        Route::put('/{newsLetter}/verify/email', 'verifyEmail')->name('verify.email');
        Route::put('/{newsLetter}/verify/mobile', 'verifyMobile')->name('verify.mobile');
    });
    // End NewsLetters Routes

    //Start Histories Routes
    Route::prefix('/histories')->controller(HistoryController::class)->as('histories.')->group(function () {
        Route::get('/ajax', 'histories')->name('ajax');
        Route::delete('/multiple/destroy', 'multipleDestroy')->name('multiple.destroy');
        Route::get('/export', 'export')->name('export');
    });
    Route::resource('histories', HistoryController::class);
    // End Histories Routes

    //Start File Manager Routes
    Route::prefix('/file/manager')->controller(FileManagerController::class)->as('file.manager.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/ajax', 'list')->name('ajax');
        Route::delete('/', 'destroy')->name('destroy');
        Route::post('/make/directory', 'makeDirectory')->name('make.directory');
        Route::post('/rename/file/or/folder', 'rename')->name('rename.directory');
        Route::post('/upload', 'upload')->name('upload');
        Route::post('/download', 'download')->name('download');
        Route::delete('/multiple/destroy', 'multipleDestroy')->name('multiple.destroy');
        Route::get('/export', 'export')->name('export');
        Route::post('/directory/details', 'showDetails')->name('show.details');
        Route::post('/ftp/image', 'ftpImage')->name('image.ftp');
    });
    // End File Manager Routes

    //Start Configs Routes
    Route::prefix('/configs')->controller(ConfigController::class)->as('configs.')->group(function () {
        Route::delete('/multiple/destroy', 'deletes')->name('multiple.destroy');
        Route::patch('/main/update', 'mainUpdate')->name('main.update');
    });
    Route::resource('configs', ConfigController::class)->except('show');
    Route::get('/primitive/configs', [ConfigController::class, 'getMains'])->name('configs.mains');

    // End Configs Routes

    //Start Reports Routes
    Route::prefix('/reports')->controller(ReportController::class)->as('reports.')->group(function () {
        Route::get('/ajax', 'reports')->name('ajax');
        Route::delete('/multiple/destroy', 'multipleDestroy')->name('multiple.destroy');
        Route::get('/export', 'export')->name('export');
        Route::get('/sms', 'index')->name('sms');
        Route::get('/email', 'index')->name('email');
    });
    Route::resource('reports', ReportController::class);
    /** this route must be the last */
    Route::get('reports/logs/viewer/{type?}', [ReportController::class, 'logViewer'])->name('logs.viewer');
    // End Reports Routes
});
// End Admin Routes

// redirect after google two fa to previous route
Route::post('/2fa/2faVerify', function () {
    return redirect(URL()->previous());
})->name('2faVerify')->middleware('2fa');

Route::get('test', function () {
    $countSuperAdmin = User::whereHas('roles', function ($query) {
        $query->where('name', 'superadmin');
    })->count();
    dd($countSuperAdmin);
})->name('test');


