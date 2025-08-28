<?php

namespace MkamelMasoud\StarterCoreKit\Interfaces;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface SortableInterface
{
    public function setSortableColumns(array $sortableColumns): void;

}