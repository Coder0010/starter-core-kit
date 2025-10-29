<?php

namespace MkamelMasoud\StarterCoreKit\Core\Ai;

use MkamelMasoud\StarterCoreKit\Contracts\AiProviderContract;
use MkamelMasoud\StarterCoreKit\Core\Ai\Traits\{BuildRequestTrait, HandlesValidationTrait, ManageStateTrait};
use MkamelMasoud\StarterCoreKit\Exceptions\AiConfigNotFoundException;
use MkamelMasoud\StarterCoreKit\Traits\Support\SupportCacheTrait;

abstract class BaseAIProvider implements AiProviderContract
{
    use HandlesValidationTrait, ManageStateTrait, BuildRequestTrait, SupportCacheTrait;

    protected string $provider;
    protected ?string $apiKey;
    protected string $baseUrl;
    protected string $version;
    protected string $endPoint;

    protected ?string $model;
    protected int $maxTokens = 2000;
    protected float $temperature = 0.7;

    protected int $attempts;
    protected int $delay;
    protected int $timeout;

    protected bool $useCache = true;
    protected array $messages = [];
    protected array $headers = [];
    protected array $payload = [];

    protected array $defaultOptions = [
        'temperature' => 0.7,
        'max_tokens' => 2000,
        'top_p' => 1,
        'frequency_penalty' => 0,
        'presence_penalty' => 0,
    ];

    protected ?string $traceId = null;

    public function __construct()
    {
        $this->loadConfig();
    }

    /**
     * @throws AiConfigNotFoundException
     */
    protected function loadConfig(array $overrides = []): void
    {
        $this->provider = config("starter-core-kit.ai.default");

        if (!config("starter-core-kit.ai.providers.{$this->provider}")) {
            AiConfigNotFoundException::make($this->provider, 'provider');
        }

        $config = array_merge(
            config("starter-core-kit.ai.providers.{$this->provider}", []),
            $overrides
        );

        $this->apiKey = $config['api_key'] ?? null;
        $this->baseUrl = rtrim($config['base_url'] ?? '', '/');
        $this->version = ltrim($config['version'] ?? '', '/');
        $this->endPoint = ltrim($config['end_point'] ?? '', '/');
        $this->model = $config['model'] ?? null;

        $this->attempts = (int)config('starter-core-kit.ai.retry.attempts', 3);
        $this->delay = (int)config('starter-core-kit.ai.retry.delay', 100);
        $this->timeout = (int)config('starter-core-kit.ai.timeout', 30);
        $this->useCache = (bool)config("starter-core-kit.ai.cache.enabled", true);

        $this->traceId = uniqid('ai_', true);
    }

    /**
     * @throws AiConfigNotFoundException
     */
    public function boot(): self
    {
        $this->initialize();
        $this->validateConfigOrFail();
        $this->getAvailableModels();
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withModel(string $model): self
    {
        $this->model = $model;
        $this->setPayload([
            'model' => $this->model,
        ]);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withTemperature(float $temperature): self
    {
        $this->temperature = max(0, min(2, $temperature));
        $this->setPayload([
            'temperature' => $this->temperature,
        ]);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withMaxTokens(int $maxTokens): self
    {
        $this->maxTokens = max(1, $maxTokens);
        $this->setPayload([
            'max_tokens' => $this->maxTokens,
        ]);
        return $this;
    }

    /**
     * Log a message
     */
    protected function log(string $level, string $message, array $context = []): void
    {
        if (!config('starter-core-kit.ai.logging.enabled', true)) {
            return;
        }
        $context['provider'] = $this->provider;
        $context['model'] = $this->model;
        $context['trace_id'] = $this->traceId;
        logger()->{$level}($message, $context);
    }

}
