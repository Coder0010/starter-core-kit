<?php

namespace MkamelMasoud\StarterCoreKit\Support\Factories;

use MkamelMasoud\StarterCoreKit\Contracts\AiProviderContract;
use MkamelMasoud\StarterCoreKit\Exceptions\{AiConfigNotFoundException, AiProviderNotFoundException};
use MkamelMasoud\StarterCoreKit\Services\Ai\AiProviders\{OpenAiProviderService, RouterAiProviderService};

class AiClientFactory
{
    /**
     * @throws AiProviderNotFoundException|AiConfigNotFoundException
     */
    public static function make(?string $provider = null): AiProviderContract
    {
        $provider = $provider ?? config('starter-core-kit.ai.default');

        $instance = match ($provider) {
            'openai' => app(OpenAiProviderService::class),
            'router' => app(RouterAiProviderService::class),
            default => throw AiProviderNotFoundException::make($provider),
        };

        return $instance->boot();
    }
}
