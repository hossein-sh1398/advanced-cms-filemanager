<?php

namespace App\Utility;

use ReflectionClass;

class Table
{
    public  $isPaginate;
    public  $isSearch;
    public  $isCsv;
    public  $isExcel;
    public  $isPdf;
    public  $isFilter;
    public  $isMore;
    public  $pageSize;
    public  $columnSort;
    public  $sortDirection;

    public function __construct($array = [])
    {
        $this->isPaginate = true;
        $this->isSearch = true;
        $this->isCsv = true;
        $this->isExcel = true;
        $this->isPdf = true;
        $this->isFilter = true;
        $this->isMore = true;
        $this->pageSize = 10;
        $this->columnSort = 'created_at';
        $this->sortDirection = 'desc';

        if (count($array)) {
            foreach ($array as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    public function toArray()
    {
        $class = new ReflectionClass($this);
        $list = [];

        foreach ($class->getProperties() as  $value) {
            if (property_exists($this, $value->name)) {
                $list[$value->name] = $this->{$value->name};
            }
        }
        return $list;
    }

    public function get($query, $fields)
    {
        $sort = request()->query('sort')  ?? 'id';
        $dir = request()->query('dir')  ?? 'desc';
        $all = (bool) request()->query('all', false);

        $query = $query->select($fields)->when($this->isSearch && ! $all, function ($q) {
            $q->search(request()->input('query')  ?? '');
        })->orderBy($sort, $dir);

        if ($this->isPaginate && ! $all) {

            $size = request()->input('size')  ?? 10;

            return $query->paginate($size)->withQueryString();
        } else {
            return $query->get();
        }
    }
}
