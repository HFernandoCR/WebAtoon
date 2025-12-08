<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Rankings') }}
            </h2>
            <svg class="h-8 w-8 text-indigo-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M19 5H5C3.89543 5 3 5.89543 3 7V9C3 10.1046 3.89543 11 5 11H19C20.1046 11 21 10.1046 21 9V7C21 5.89543 20.1046 5 19 5Z"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M5 11C5 13.2091 6.79086 15 9 15H15C17.2091 15 19 13.2091 19 11M12 15V19M9 22H15"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </x-slot>

    <div class="min-h-screen bg-[#bfdbfe] py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/30 backdrop-blur-md shadow-lg rounded-xl overflow-hidden mt-6">
                <div class="p-12 text-center">
                    <h3 class="text-2xl font-bold text-indigo-900 mb-2">{{ __('No Active Events') }}</h3>
                    <p class="text-indigo-800 mb-6 font-medium">
                        {{ __('There are currently no events available to display rankings for.') }}
                    </p>

                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                        <a href="{{ route('events.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Create Event') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>