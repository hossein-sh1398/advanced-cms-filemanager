<?php

namespace App\Utility\Search;

trait WithSearch
{
    public function scopeSearch($query, $string)
    {
        $string = trim($string);

        $all = (bool) request()->query('all', false);

        if (! $all) {
            if ($string) {
                $columns = $this->search;
                foreach ($columns as $column) {
                    $query->orWhere($column, 'LIKE', '%' . $string . '%');
                }
            }
        }

        return $query;
    }
}
