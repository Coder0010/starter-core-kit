<?php

namespace MkamelMasoud\StarterCoreKit\Services\Ai;

use MkamelMasoud\StarterCoreKit\Support\Factories\AiClientFactory;

class AiClientService
{
    public function __construct(
        protected ?string $provider = null
    ) {}

    /**
     * Send a prompt to the AI provider and return the generated text.
     *
     * @param  string  $prompt  The user's input text to send to the AI.
     * @param  array<string, mixed>  $options  Optional configuration, such as:
     *                                         - 'system_prompt' (string): Instruction or role for the AI.
     * @return string|null The AI's response text, or null on failure.
     */
    public function ask(string $prompt, array $options = []): ?string
    {
        return AiClientFactory::make($this->provider)->ask($prompt, $options);
    }
}
