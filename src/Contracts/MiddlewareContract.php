<?php

namespace MkamelMasoud\StarterCoreKit\Contracts;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface MiddlewareContract
{
    public function handle(Request $request, Closure $next): Response;
}
