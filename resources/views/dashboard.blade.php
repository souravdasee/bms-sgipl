<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @auth
            @if (request()->user()->role->name === 'Super Admin')
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("You're logged in as Super Admin!") }}
                        </div>
                    </div>
                </div>
            @endif

            @if (request()->user()->role->name === 'Organizer')
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("You're logged in as Organizer!") }}
                        </div>
                    </div>
                </div>
            @endif

            @if (request()->user()->role->name === 'Accountant')
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("You're logged in as Accountant!") }}
                        </div>
                    </div>
                </div>
            @endif

            @if (request()->user()->role->name === 'Human Resource')
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("You're logged in as Human Resource!") }}
                        </div>
                    </div>
                </div>
            @endif

            @if (request()->user()->role->name === 'Employee')
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("You're logged in as Employee!") }}
                        </div>
                    </div>
                </div>
            @endif

            @if (request()->user()->role->name === 'Store Keeper')
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("You're logged in as Store Keeper!") }}
                        </div>
                    </div>
                </div>
            @endif

            @if (request()->user()->role->name === 'User')
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("You're logged in as User!") }}
                        </div>
                    </div>
                </div>
            @endif
        @endauth
    </div>
</x-app-layout>
