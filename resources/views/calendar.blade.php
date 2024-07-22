<x-calendar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">イベントカレンダー</a>
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="event-calendar mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                @livewire('calendar')
            </div>
        </div>
    </div>
</x-calendar-layout>
