<?php

namespace MkamelMasoud\StarterCoreKit\Services\Ai;

namespace MkamelMasoud\StarterCoreKit\Services\Ai;

use MkamelMasoud\StarterCoreKit\Contracts\AiProviderContract;
use MkamelMasoud\StarterCoreKit\Support\Factories\AiClientFactory;

class AiClientService
{
    protected AiProviderContract $client;

    public function __construct(?string $provider = null)
    {
        $this->client = AiClientFactory::make($provider);
    }

    public function setPrompt(string $role, string $prompt): self
    {
        $this->client->setPrompt($role, $prompt);
        return $this;
    }

    public function ask(string $prompt): ?string
    {
        return $this->client->ask($prompt);
    }
}
