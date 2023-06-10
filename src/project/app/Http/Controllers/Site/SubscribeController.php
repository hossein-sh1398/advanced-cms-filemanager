<?php

namespace App\Http\Controllers\Site;

use Exception;
use App\Models\NewsLetter;
use App\Http\Controllers\Controller;

class SubscribeController extends Controller
{
    public function verifyEmail(NewsLetter $newsLetter)
    {
        try {
            $newsLetter->active_email = true;

            $newsLetter->save();

            session()->flash('success', __('messages.verify-email'));
        } catch (Exception $e) {
            session()->flash('error', __('messages.error'));
        }

        return to_route('admin.news.letters.index');
    }
}
