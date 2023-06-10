<?php

namespace App\Http\Repositories;

use App\Models\User;
use Exception;

class UserRepository
{
    /**
     * register profile in database
     *
     * @param [array] $array
     * @return bool
     */
    public static function register($array)
    {
        return User::create($array);
    }

    /**
     * Undocumented function
     *
     * @param [type] $user
     * @return bool
     */
    public static function delete($user): bool
    {
        if (auth()->id() == $user->id) {
            throw new Exception('شما قادر به حذف اکانت خود نمی باشید');
        }

        if ($user->articles->count() || $user->reports->count()) {
            throw new Exception('به دلیل وابستگی قادر به حذف کاربر  انتخاب شده نمی باشید');
        }

        if ($user->roles->count()) {
            $user->roles()->sync([]);
        }

        foreach($user->comments as $comment) {
            CommentRepository::delete($comment);
        }

        return $user->delete();
    }

    /**
     * Undocumented function
     *
     * @param [type] $user
     * @return bool
     */
    public static function changeStatus($user): bool
    {
        if ($user->id == auth()->id()) {
            throw new Exception('کاربر گرامی شما قادر به تغییر وضعیت اکانت خود نمی باشید');
        }

        if ($user->account_verified_at) {
            $user->account_verified_at = null;
        } else {
            $user->account_verified_at = now();
        }

        return $user->save();
    }
}
