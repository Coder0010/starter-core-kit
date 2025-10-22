<?php

namespace MkamelMasoud\StarterCoreKit\Rules\Validation;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class NoHtmlRule implements ValidationRule
{
    /**
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value !== null && strip_tags($value) !== $value) {
            $fail('The :attribute must not contain HTML tags.');
        }
    }
}
