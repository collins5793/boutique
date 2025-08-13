<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <a href="{{ route('roles.index') }}" 
       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Voir les rôles
    </a>
    <a href="{{ route('users.index') }}" 
       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Voir les users
    </a>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
