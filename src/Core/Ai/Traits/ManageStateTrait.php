<?php

namespace MkamelMasoud\StarterCoreKit\Core\Ai\Traits;

trait ManageStateTrait
{
    public function getFullUrl(): string
    {
        return "{$this->baseUrl}/{$this->version}/{$this->endPoint}";
    }

    public function setHeaders(array $headers, bool $clean = false): self
    {
        if ($clean) {
            $this->headers = [];
        }
        foreach ($headers as $key => $value) {
            $this->headers[$key] = $value;
        }
        return $this;
    }

    protected function getHeaders(): array
    {
        return $this->headers;
    }

    public function setPayload(array $payload, bool $clean = false): self
    {
        if ($clean) {
            $this->payload = $this->defaultOptions;
        }
        $this->payload = array_merge($this->defaultOptions, $this->payload, $payload);
        return $this;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function setPrompt(string $role, string $prompt): self
    {
        if (trim($prompt) === '') {
            return $this;
        }

        $this->messages[] = [
            'role' => strtolower(trim($role)),
            'content' => $prompt,
        ];

        // No validation here — handled later
        $this->setPayload(['messages' => $this->messages]);

        return $this;
    }

}