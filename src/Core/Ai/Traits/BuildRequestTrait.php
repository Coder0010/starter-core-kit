<?php

namespace MkamelMasoud\StarterCoreKit\Core\Ai\Traits;

use Illuminate\Support\Facades\Http;

trait BuildRequestTrait
{
    protected function initialize(): void
    {
        $this->setHeaders(
            headers: [
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
                'X-AI-Provider' => $this->provider,
                'X-AI-Model' => $this->model,
                'X-Trace-ID' => $this->traceId,
            ],
            clean: true
        );
        $this->setPayload(
            payload: [
                'model' => $this->model
            ],
            clean: true
        );
    }

    /**
     * @throws \Throwable
     */
    protected function sendRequest(): string
    {
        $this->validateMessagesOrFail();
        $table = "ai_{$this->provider}_{$this->model}_responses";
        $cacheKey = "{$this->provider}_" . md5(json_encode(['payload' => $this->getPayload()]));

        $callback = function () {
            $response = $this->makeHttpRequest();
            return $this->extractResponse($response);
        };

        try {
            return $this->useCache
                ? $this->cacheRemember($table, $cacheKey, $callback)
                : $callback();
        } catch (\Throwable $e) {
            $this->log('error', "AI Error {$e->getMessage()}", [
                'url' => $this->getFullUrl(),
                'payload' => $this->getPayload(),
                'headers' => $this->getHeaders(),
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    protected function makeHttpRequest(): array
    {
        return Http::withHeaders($this->getHeaders())
            ->timeout($this->timeout)
            ->retry($this->attempts, $this->delay)
            ->post($this->getFullUrl(), $this->getPayload())
            ->throw()
            ->json();
    }

    protected function extractResponse(array|string|null $response): mixed
    {
        // Handle simple cases first
        if (is_string($response)) {
            return trim($response);
        }

        if (empty($response) || !is_array($response)) {
            return $response;
        }

        // Common known response paths for various AI APIs
        $patterns = [
            'choices.0.message.content',           // OpenAI chat format
            'choices.0.text',                      // OpenAI completion format
            'output.text',                         // Router / custom format
            'content.0.text',                      // Anthropic Claude format
            'candidates.0.content.parts.0.text',   // Gemini / Vertex AI
            'data.0.output_text',                  // Some generic fallback
        ];

        foreach ($patterns as $path) {
            $value = data_get($response, $path);
            if (!empty($value) && is_string($value)) {
                return trim($value);
            }
        }

        // Sometimes the text might be an array of segments
        $flattened = collect($patterns)
            ->map(fn($p) => data_get($response, $p))
            ->flatten()
            ->filter(fn($v) => is_string($v) && trim($v) !== '')
            ->implode("\n");

        if ($flattened !== '') {
            return trim($flattened);
        }

        // If we still can’t detect a clean string, log for visibility
        logger()->warning('AI extractResponse: could not normalize response', [
            'keys' => array_keys($response),
        ]);

        // Return raw for debugging / fallback
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableModels(): array
    {
        return (array)config('starter-core-kit.ai.models.default', []);
    }
}