<?php

namespace MkamelMasoud\StarterCoreKit\Contracts;

interface AiProviderContract
{
    /**
     * @param  array<string, mixed>  $options
     */
    public function ask(string $prompt, array $options = []): ?string;
}
