<?php

namespace MkamelMasoud\StarterCoreKit\Exceptions;

use Exception;

class AiProviderNotFoundException extends Exception
{
    protected $message = 'Ai provider not found';
}