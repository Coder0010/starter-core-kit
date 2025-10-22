<?php

namespace MkamelMasoud\StarterCoreKit\Services\Ai\AiProviders;

use Illuminate\Support\Facades\Http;
use MkamelMasoud\StarterCoreKit\Contracts\AiProviderContract;

class RouterAiProviderService implements AiProviderContract
{
    public function __construct(
        protected ?string $apikey = null,
        protected ?string $endpoint = null,
        protected ?string $model = null
    ) {
        $this->apikey = $apikey ?? config('starter-core-kit.ai.providers.router.apikey');
        $this->endpoint = $endpoint ?? config('starter-core-kit.ai.providers.router.endpoint');
        $this->model = $model ?? config('starter-core-kit.ai.providers.router.model');
    }

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
                ])
                ->throw();

            $json = $response->json();
            logger()->info('Router AI response', ['response' => $json]);

            return $json['output']['text'] ?? null;
        } catch (\Throwable $e) {
            logger()->error('Router AI request failed', ['error' => $e->getMessage()]);
        }

        return null;
    }
}
