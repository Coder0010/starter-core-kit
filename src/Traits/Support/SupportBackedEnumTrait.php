<?php

namespace MkamelMasoud\StarterCoreKit\Traits\Support;

use Illuminate\Support\Str;

trait SupportBackedEnumTrait
{
    public static function all(): array
    {
        return collect(self::cases())->map(fn($case) => [
            'name'  => $case->name,
            'value' => $case->value,
            'label' => $case->getLabel(),
        ])->toArray();
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getLabel(): string
    {
        return __("enums.{$this->getTransKey()}.{$this->value}");
    }

    public function getTransKey(): string
    {
        return Str::of(class_basename($this))
            ->replaceLast('Enum', '')
            ->snake()
            ->lower()
            ->plural();
    }
}
