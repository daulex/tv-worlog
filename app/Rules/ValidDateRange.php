<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidDateRange implements ValidationRule
{
    private string $startDateField;

    private mixed $startDate;

    public function __construct(string $startDateField, mixed $startDate)
    {
        $this->startDateField = $startDateField;
        $this->startDate = $startDate;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value) || empty($this->startDate)) {
            return; // Both dates are optional
        }

        $startDate = is_string($this->startDate) ? strtotime($this->startDate) : $this->startDate->timestamp;
        $endDate = is_string($value) ? strtotime($value) : $value->timestamp;

        if ($endDate < $startDate) {
            $fail("The {$attribute} must be after or equal to {$this->startDateField}.");
        }
    }
}
