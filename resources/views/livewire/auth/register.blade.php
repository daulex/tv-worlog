<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- First Name -->
            <flux:input
                name="first_name"
                :label="__('First name')"
                type="text"
                required
                autofocus
                autocomplete="given-name"
                :placeholder="__('First name')"
            />

            <!-- Last Name -->
            <flux:input
                name="last_name"
                :label="__('Last name')"
                type="text"
                required
                autocomplete="family-name"
                :placeholder="__('Last name')"
            />

            <!-- Personal Code -->
            <flux:input
                name="pers_code"
                :label="__('Personal code')"
                type="text"
                required
                autocomplete="off"
                :placeholder="__('Personal code')"
            />

            <!-- Date of Birth -->
            <flux:input
                name="date_of_birth"
                :label="__('Date of birth')"
                type="date"
                required
                autocomplete="bday"
                :placeholder="__('Date of birth')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full">
                    {{ __('Create account') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    </div>
</x-layouts.auth>
