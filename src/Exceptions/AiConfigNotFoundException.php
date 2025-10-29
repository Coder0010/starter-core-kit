<?php

namespace MkamelMasoud\StarterCoreKit\Exceptions;

use Exception;

class AiConfigNotFoundException extends Exception
{
    public static function make(string $provider, string $config): self
    {
        return new self("AI provider [{$provider}] missing config [{$config}].");
    }
}