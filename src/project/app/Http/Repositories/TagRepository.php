<?php

namespace App\Http\Repositories;

use App\Models\Tag;
use App\Utility\Table;
use Exception;
use Illuminate\Http\Request;

class TagRepository
{
    /**
     * update Tag
     *
     * @param [string] $title
     * @return bool
     */
    public static function update(Tag $tag, $title): bool
    {
        return $tag->update(['title' => $title]);
    }

    /**
     * create Tag
     *
     * @param [string] $title
     * @return Tag
     */
    public static function create($title): Tag
    {
        return Tag::create(['title' => $title]);
    }

    /**
     * Delete Tag
     *
     * @param Tag $Tag
     * @return bool
     */
    public static function delete(Tag $tag): bool
    {
        if ($tag->articles->isNotEmpty()) {
            throw new Exception('به دلیل وابسته بودن مقاله به کلمه کلیدی مذکور ، آیتم مورد نظر قابل حذف نمی باشد');
        }

        return $tag->delete();
    }

    /**
     * Delete multiple selected tag
     *
     * @param array $ids
     * @return bool
     */
    public static function multipleDestroy(array $ids): bool
    {
        $tags = Tag::with('articles')->whereIn('id', $ids)->get();

        foreach ($tags as $tag) {
            self::delete($tag);
        }

        return true;
    }
}
