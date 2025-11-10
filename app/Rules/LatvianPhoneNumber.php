<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LatvianPhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // Phone is nullable
        }

        // Remove spaces, dashes, parentheses
        $cleaned = preg_replace('/[\s\-\(\)]/', '', $value);

        // Validate Latvian phone formats:
        // +371 XXXXXXXX (8 digits after country code)
        // 2XXXXXXX (7 digits, mobile)
        // 6XXXXXXX (7 digits, landline)
        if (! preg_match('/^(\+371\s?)?[26]\d{7}$/', $cleaned)) {
            $fail('The :attribute must be a valid Latvian phone number (e.g., +371 12345678 or 21234567).');
        }
    }
}
