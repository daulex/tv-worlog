<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LatvianPersonalCode implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check format: DDMMYY-NNNNN
        if (! preg_match('/^\d{6}-\d{5}$/', $value)) {
            $fail('The :attribute must be in the format DDMMYY-NNNNN.');

            return;
        }

        // Extract parts
        $parts = explode('-', $value);
        $datePart = $parts[0];
        $checksumPart = $parts[1];

        // Extract day, month, year
        $day = (int) substr($datePart, 0, 2);
        $month = (int) substr($datePart, 2, 2);
        $year = (int) substr($datePart, 4, 2);
        $century = (int) substr($checksumPart, 0, 1);

        // Determine century
        if ($century === 0) {
            $year += 1800;
        } elseif ($century === 1) {
            $year += 1900;
        } elseif ($century === 2) {
            $year += 2000;
        } else {
            $year += 2100;
        }

        // Validate date
        if (! checkdate($month, $day, $year)) {
            $fail('The :attribute contains an invalid date.');

            return;
        }

        // Validate checksum (simplified version - full implementation would be more complex)
        $weights = [1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        $digits = str_replace('-', '', $value);
        $sum = 0;

        for ($i = 0; $i < 10; $i++) {
            $sum += (int) $digits[$i] * $weights[$i];
        }

        $remainder = $sum % 11;
        $checksum = (int) $digits[10];

        if ($remainder === 10) {
            // Special case for remainder 10
            $weights2 = [3, 7, 2, 4, 10, 3, 5, 9, 4, 6, 8];
            $sum2 = 0;
            for ($i = 0; $i < 11; $i++) {
                $sum2 += (int) $digits[$i] * $weights2[$i];
            }
            $remainder2 = $sum2 % 11;
            if ($remainder2 === 10 && $checksum === 1) {
                return; // Valid special case
            } elseif ($remainder2 === $checksum) {
                return; // Valid
            }
        } elseif ($remainder === $checksum) {
            return; // Valid
        }

        $fail('The :attribute contains an invalid checksum.');
    }
}
