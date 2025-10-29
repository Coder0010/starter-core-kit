<?php

namespace MkamelMasoud\StarterCoreKit\Services\Ai\AiProviders;

use MkamelMasoud\StarterCoreKit\Core\Ai\BaseAIProvider;

class OpenAiProviderService extends BaseAIProvider
{
    /**
     * {@inheritdoc}
     */
    public function ask(string $prompt, array $options = []): ?string
    {
        return $this
            ->setPayload([
                'max_tokens' => 300,
                'temperature' => 0.2,
            ])
            ->setPrompt(role: 'user', prompt: $prompt)
            ->sendRequest();
    }

}
