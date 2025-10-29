<?php

namespace MkamelMasoud\StarterCoreKit\Exceptions;

use Exception;

class AiProviderNotFoundException extends Exception
{
    public static function make(string $provider): self
    {
        return new self("AI provider [{$provider}] is not configured.");
    }
}