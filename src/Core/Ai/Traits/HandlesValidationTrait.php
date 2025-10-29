<?php

namespace MkamelMasoud\StarterCoreKit\Core\Ai\Traits;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use MkamelMasoud\StarterCoreKit\Exceptions\AiConfigNotFoundException;

trait HandlesValidationTrait
{
    /**
     * Validate the configuration
     * @throws AiConfigNotFoundException
     */
    protected function validateConfigOrFail(): void
    {
        $validator = Validator::make([
            'apiKey' => $this->apiKey,
            'baseUrl' => $this->baseUrl,
            'version' => $this->version,
            'endPoint' => $this->endPoint,
        ], [
            'apiKey' => 'required|string',
            'baseUrl' => 'required|regex:/^https?:\/\/[^\s]+$/i',
            'version' => 'required|regex:/^v?\d+(\.\d+)?$/',
            'endPoint' => 'required',
        ]);

        if ($validator->fails()) {
            throw new AiConfigNotFoundException(
                "AI provider [{$this->provider}] config is invalid: " . $validator->errors()->first()
            );
        }
    }

    /**
     * Validate the full request before sending
     */
    protected function validateMessagesOrFail(): void
    {
        $validator = Validator::make([
            'messages' => $this->messages,
        ], [
            'messages' => 'required|array|min:1',
            'messages.*.role' => 'required|string|in:system,user,assistant',
            'messages.*.content' => 'required|string|min:1',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException(
                "Invalid AI request payload: " . $validator->errors()->first()
            );
        }
    }

}