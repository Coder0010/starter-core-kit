<?php

namespace MkamelMasoud\StarterCoreKit\Core;

use Illuminate\Http\Request;

abstract class BaseDto
{
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (!property_exists($this, $key)) {
                continue;
            }

            $type = $this->getPropertyType($key);

            // 🔹 Handle Enums automatically
            if ($type && enum_exists($type)) {
                $this->$key = $this->castToEnum($type, $value);
                continue;
            }

            $this->$key = $value;
        }
    }

    /**
     * Create DTO from request input.
     */
    public static function fromRequest(Request $request): static
    {
        $instance = new static();
        $fields = array_keys(get_object_vars($instance));

        return new static($request->only($fields));
    }

    /**
     * Convert DTO to array.
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * Get property type using reflection.
     */
    protected function getPropertyType(string $property): ?string
    {
        $reflection = new \ReflectionProperty($this, $property);
        $type = $reflection->getType();

        if (!$type) {
            return null;
        }

        if ($type instanceof \ReflectionNamedType) {
            return $type->getName();
        }

        // If it's a union type, you can decide how to handle it:
        if ($type instanceof \ReflectionUnionType) {
            // Example: return first type name (or handle differently)
            $types = $type->getTypes(); // array of ReflectionNamedType
            return $types[0]->getName(); // just pick the first one
        }

        return null;
    }

    /**
     * Cast a string or value to Enum (safe handling for nulls).
     */
    protected function castToEnum(string $enumClass, mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        // If it's already the correct Enum, return as is
        if ($value instanceof $enumClass) {
            return $value;
        }

        // Try from() safely
        return $enumClass::tryFrom($value) ?? null;
    }
}
