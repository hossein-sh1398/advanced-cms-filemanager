<?php

namespace App\Utility\Search;

trait WithFilter
{
    public function scopeFilters($query, array $array = [])
    {
        $all = (bool) request()->query('all', false);

        if (! $all) {
            if (count($array)) {
                foreach ($array as $value) {
                    $query->where($value['key'], $value['op'], $value['value']);
                }
            }
        }

        return $query;
    }
}
