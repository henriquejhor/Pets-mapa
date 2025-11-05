<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight text-center">
            {{ __('Mapa Central') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#8AC7CF] overflow-hidden shadow-sm sm:rounded-lg font-semibold text-xl"> <!-- COR FUNDO ATRAS DO MAPA-->
                <div class="p-6 text-white text-gray-900 text-center">
                    {{ __("Faça Login para publicar Pets") }}

                    <h1 class="text-2xl font-semibold mb-4 text-white-xl">Mapa de Pets</h1>
                    <livewire:pets-map />

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
