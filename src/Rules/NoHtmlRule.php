<?php

namespace MkamelMasoud\StarterCoreKit\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoHtmlRule implements Rule
{
    public function passes($attribute, $value)
    {
        return $value === null || strip_tags($value) === $value;
    }

    public function message()
    {
        return 'The :attribute must not contain HTML tags.';
    }
}