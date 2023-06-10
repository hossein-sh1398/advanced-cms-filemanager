<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function __construct($enableAuthorize = true)
    {
        parent::__construct($enableAuthorize);
    }

    public function index()
    {
        return view('admin.pages.home.index');
    }

    public function logViewer($type = false)
    {
        if (!isLogs()) {
            session()->flash('error', 'هیچ خطایی برای نمایش وجود ندارد');
            return redirect(route('admin.index'));
        }

        if ($type === 'clear'){
            $files = glob(storage_path('logs/*'));
            foreach ($files as $file){
                File::delete($file);
            }
            session()->flash('success', 'تمامی خطاها حذف گردید');
            return redirect(route('admin.index'));
        }
        return view('admin.pages.logs.index');
    }
}
