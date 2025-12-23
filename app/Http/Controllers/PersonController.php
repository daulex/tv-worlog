<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Rules\LatvianPersonalCode;
use App\Rules\ValidDateRange;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'pers_code' => ['required', 'string', 'unique:people,pers_code', new LatvianPersonalCode],
            'phone' => ['nullable', 'string', 'max:20', new \App\Rules\LatvianPhoneNumber],
            'email' => 'required|email:rfc|unique:people,email',
            'date_of_birth' => 'required|date|before:today',
            'address' => 'nullable|string|max:1000',
            'starting_date' => 'nullable|date|before_or_equal:today',
            'last_working_date' => ['nullable', 'date', 'before_or_equal:today', new ValidDateRange('starting_date', $request->starting_date)],
            'position' => 'nullable|string|max:255',
            'status' => 'required|in:Candidate,Employee,Retired',
            'client_id' => 'nullable|exists:clients,id',
            'vacancy_id' => 'nullable|exists:vacancies,id',
            'linkedin_profile' => 'nullable|url|max:500',
            'github_profile' => 'nullable|url|max:500',
            'portfolio_url' => 'nullable|url|max:500',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'emergency_contact_phone' => ['nullable', 'string', 'max:255', new \App\Rules\LatvianPhoneNumber],
        ]);

        Person::create($request->only([
            'first_name', 'last_name', 'pers_code', 'phone', 'email', 'date_of_birth', 'address',
            'starting_date', 'last_working_date', 'position', 'status', 'client_id', 'vacancy_id',
            'linkedin_profile', 'github_profile', 'portfolio_url', 'emergency_contact_name',
            'emergency_contact_relationship', 'emergency_contact_phone',
        ]));

        return redirect()->route('people.index')->with('message', 'Person created successfully.');
    }
}
