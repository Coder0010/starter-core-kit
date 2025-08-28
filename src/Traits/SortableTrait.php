<?php

namespace MkamelMasoud\StarterCoreKit\Traits;

use Illuminate\Database\Query\Builder;

trait SortableTrait
{

    public function sort()
    {
        $sortableAttributes = explode(',', request()->input('sort'));
        foreach ($sortableAttributes as $sortAttribute) {
            $direction = 'desc';
            if (\Str::startsWith($sortAttribute, '-')) {
                $direction = 'asc';
                $sortAttribute = substr($sortAttribute, 1);
            }
            return $this->orderBy($sortAttribute, $direction);
        }
    }
}