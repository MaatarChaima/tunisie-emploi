<x-app-layout>
    <x-slot name="title">Tableau de bord</x-slot>

    <x-slot name="header">
        <x-page-header
            title="Tableau de bord"
            subtitle="Bienvenue, {{ Auth::user()->name }}"
            icon="chart"
        />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Vous êtes connecté !") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
