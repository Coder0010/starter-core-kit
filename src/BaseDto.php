<?php

namespace MkamelMasoud\StarterCoreKit;

use Illuminate\Http\Request;

abstract class BaseDto
{
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public static function fromRequest(Request $request): static
    {
        return new static($request->only(array_keys(get_object_vars(new static()))));
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}