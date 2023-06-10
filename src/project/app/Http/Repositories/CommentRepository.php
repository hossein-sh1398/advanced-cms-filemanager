<?php

namespace App\Http\Repositories;

class CommentRepository
{
    public static function delete($comment)
    {
        if ($comment->replies->count()) {
            $comment->replies()->delete();
        }

        $comment->delete();
    }

}
