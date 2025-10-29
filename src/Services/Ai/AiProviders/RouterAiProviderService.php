<?php

namespace MkamelMasoud\StarterCoreKit\Services\Ai\AiProviders;

use MkamelMasoud\StarterCoreKit\Core\Ai\BaseAIProvider;

class RouterAiProviderService extends BaseAIProvider
{
    /**
     * {@inheritdoc}
     */
    public function ask(string $prompt, array $options = []): ?string
    {
        return $this
            ->setHeaders([
                "HTTP-Referer" => config('app.url'),
                "X-Title" => config('app.name'),
            ])
            ->setPrompt(role: 'user', prompt: $prompt)
            ->sendRequest();
    }

}
