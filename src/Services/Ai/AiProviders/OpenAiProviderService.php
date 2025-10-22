<?php

namespace MkamelMasoud\StarterCoreKit\Services\Ai\AiProviders;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use MkamelMasoud\StarterCoreKit\Contracts\AiProviderContract;

class OpenAiProviderService implements AiProviderContract
{
    public function __construct(
        protected ?string $apikey = null,
        protected ?string $endpoint = null,
        protected ?string $model = null
    ) {
        $this->apikey = $apikey ?? config('starter-core-kit.ai.providers.openai.apikey');
        $this->endpoint = $endpoint ?? config('starter-core-kit.ai.providers.openai.endpoint');
        $this->model = $model ?? config('starter-core-kit.ai.providers.openai.model');
    }

    public function ask(string $prompt, array $options = []): ?string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apikey,
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
            ])
                ->timeout(20)
                ->retry(2, 500)
                ->post($this->endpoint, [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $options['system_prompt'] ?? 'You are a helpful assistant.',
                        ],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => $options['max_tokens'] ?? 200,
                    'temperature' => $options['temperature'] ?? 0.2,
                ])
                ->throw();

            $json = $response->json();
            logger()->info('OpenAI response', ['prompt' => $prompt, 'response' => $json]);

            return $json['choices'][0]['message']['content'] ?? null;
        } catch (RequestException $e) {
            logger()->error('OpenAI request failed', [
                'prompt' => $prompt,
                'status' => optional($e->response)->status(),
                'error' => $e->getMessage(),
            ]);
        } catch (\Throwable $e) {
            logger()->error('OpenAI client unexpected error', [
                'prompt' => $prompt,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }
}
