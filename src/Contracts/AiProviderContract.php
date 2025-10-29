<?php

namespace MkamelMasoud\StarterCoreKit\Contracts;

interface AiProviderContract
{
    /**
     * Send a prompt to the AI and get a response
     * 
     * @param string $prompt The input prompt
     * @param array $options Additional options for the request
     *
     * @return string|null The generated response or null on failure
     * @throws \Throwable
     */
    public function ask(string $prompt, array $options = []): ?string;

    /**
     * Set the model to be used for the next request
     *
     * @param string $model Model identifier
     * @return self
     */
    public function withModel(string $model): self;

    /**
     * Set the temperature parameter for the next request
     *
     * @param float $temperature Value between 0 and 2
     * @return self
     */
    public function withTemperature(float $temperature): self;

    /**
     * Set the maximum number of tokens to generate
     *
     * @param int $maxTokens Maximum number of tokens
     * @return self
     */
    public function withMaxTokens(int $maxTokens): self;

}
