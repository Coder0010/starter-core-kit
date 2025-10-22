<?php

namespace MkamelMasoud\StarterCoreKit\Support\Factories;

use InvalidArgumentException;
use MkamelMasoud\StarterCoreKit\Contracts\AiProviderContract;
use MkamelMasoud\StarterCoreKit\Services\Ai\AiProviders\OpenAiProviderService;
use MkamelMasoud\StarterCoreKit\Services\Ai\AiProviders\RouterAiProviderService;

class AiClientFactory
{
    public static function make(?string $provider = null): AiProviderContract
    {
        $provider = $provider ?? config('starter-core-kit.ai.default');

        return match ($provider) {
            'openai' => app(OpenAiProviderService::class),
            'router' => app(RouterAiProviderService::class),
            default => throw new InvalidArgumentException("Unsupported AI provider: {$provider}"),
        };
    }
}
