<?php

namespace Mkamel\StarterCoreKit\Interfaces;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface MiddlewareInterface
{
    public function handle(Request $request, Closure $next): Response;

}