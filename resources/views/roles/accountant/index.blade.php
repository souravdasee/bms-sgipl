<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Accountant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @auth
            @if (request()->user()->role->name === 'Super Admin' || request()->user()->role->name === 'Accountant')
                <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div>


                                <button class="hover:bg-gray-500"><a href="/invoice">Invoice</a></button>



                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endauth
    </div>
</x-app-layout>
