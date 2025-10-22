<?php

namespace MkamelMasoud\StarterCoreKit\Traits\Support;

/** @phpstan-ignore trait.unused */
trait SupportSortableTrait
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
