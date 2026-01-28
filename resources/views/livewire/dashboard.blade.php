<div>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Overview of your staff and equipment management system</p>
    </div>

    <!-- People Statistics -->
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">People</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Candidates Card -->
            <a href="{{ route('people.index', ['statusFilter' => 'Candidate']) }}" class="block">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Candidates</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['candidates'] }}
                            </p>
                        </div>
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                            <flux:icon name="user-plus" class="w-8 h-8 text-yellow-600 dark:text-yellow-400" />
                        </div>
                    </div>
                </div>
            </a>

            <!-- Employees Card -->
            <a href="{{ route('people.index', ['statusFilter' => 'Employee']) }}" class="block">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Employees</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['employees'] }}
                            </p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                            <flux:icon name="user-group" class="w-8 h-8 text-green-600 dark:text-green-400" />
                        </div>
                    </div>
                </div>
            </a>

            <!-- Retired Card -->
            <a href="{{ route('people.index', ['statusFilter' => 'Retired']) }}" class="block">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Retired</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['retired'] }}</p>
                        </div>
                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-full">
                            <flux:icon name="user-minus" class="w-8 h-8 text-gray-600 dark:text-gray-400" />
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Other Statistics -->
    <div>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Resources</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Clients Card -->
            <a href="{{ route('clients.index') }}" class="block">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Clients</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['clients'] }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                            <flux:icon name="building-office" class="w-8 h-8 text-blue-600 dark:text-blue-400" />
                        </div>
                    </div>
                </div>
            </a>

            <!-- Vacancies Card -->
            <a href="{{ route('vacancies.index') }}" class="block">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Vacancies</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['vacancies'] }}
                            </p>
                        </div>
                        <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                            <flux:icon name="briefcase" class="w-8 h-8 text-purple-600 dark:text-purple-400" />
                        </div>
                    </div>
                </div>
            </a>

            <!-- Active Equipment Card -->
            <a href="{{ route('equipment.index') }}" class="block">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Equipment</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                                {{ $stats['active_equipment'] }}</p>
                        </div>
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full">
                            <flux:icon name="computer-desktop" class="w-8 h-8 text-indigo-600 dark:text-indigo-400" />
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Upcoming Birthdays -->
    <div class="mt-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Upcoming Birthdays</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            @if($upcomingBirthdays->isEmpty())
                <p class="text-gray-600 dark:text-gray-400">No upcoming birthdays in next year.</p>
            @else
                <div class="space-y-4">
                    @foreach($upcomingBirthdays as $birthday)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    <a href="{{ route('people.show', $birthday['id']) }}" class="text-gray-900 dark:text-gray-100 hover:underline">
                                    {{ $birthday['name'] }}
                                    </a>
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $birthday['date_of_birth'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    in {{ $birthday['days'] }} day{{ $birthday['days'] !== 1 ? 's' : '' }}
                                </p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $birthday['age'] }} years old
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        </div>
    </div>
    

</div>