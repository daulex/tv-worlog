<?php

namespace App\Actions\Fortify;

use App\Models\Person;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): Person
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'pers_code' => ['required', 'string', 'max:255', Rule::unique(Person::class)],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(Person::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return Person::create([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'pers_code' => $input['pers_code'],
            'date_of_birth' => $input['date_of_birth'],
            'email' => $input['email'],
            'password' => $input['password'],
            'status' => 'Candidate',
        ]);
    }
}
