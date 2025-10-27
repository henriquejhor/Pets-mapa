<!-- Modal do PetForm -->
<div x-data="{ open: @entangle('showModal') }" x-cloak>
    <!-- Fundo escuro -->
    <div 
        x-show="open" 
        class="fixed inset-0 bg-black bg-opacity-50 z-40"
        x-transition.opacity
        @click="open = false; $wire.showModal = false"
    ></div>

    <!-- Conteúdo do modal -->
    <div 
        x-show="open" 
        class="fixed inset-0 flex items-center justify-center z-50"
        x-transition
    >
        <div class="bg-white rounded shadow-lg max-w-lg w-full p-4" @click.away="open = false; $wire.showModal = false">
            <!-- Cabeçalho -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Cadastrar Pet</h2>
                <button @click="open = false; $wire.showModal = false" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <!-- Mensagem de sucesso -->
            @if(session()->has('message'))
                <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Formulário -->
            <form wire:submit.prevent="submit" enctype="multipart/form-data">
                <div class="mb-2">
                    <label>Nome do pet</label>
                    <input type="text" wire:model="name" class="w-full border px-2 py-1 rounded">
                </div>

                <div class="mb-2">
                    <label>Tipo</label>
                    <select wire:model="type" class="w-full border px-2 py-1 rounded">
                        <option value="">Selecione</option>
                        <option value="perdido">Perdido</option>
                        <option value="encontrado">Encontrado</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label>Descrição</label>
                    <textarea wire:model="description" class="w-full border px-2 py-1 rounded"></textarea>
                </div>

                <div class="mb-2">
                    <label>Telefone</label>
                    <input type="text" wire:model="telefone" class="w-full border px-2 py-1 rounded">
                </div>

                <div class="mb-2">
                    <label>Cidade</label>
                    <input type="text" wire:model="city" class="w-full border px-2 py-1 rounded">
                </div>

                <div class="mb-2">
                    <label>Latitude</label>
                    <input type="text" wire:model="latitude" class="w-full border px-2 py-1 rounded" readonly>
                </div>

                <div class="mb-2">
                    <label>Longitude</label>
                    <input type="text" wire:model="longitude" class="w-full border px-2 py-1 rounded" readonly>
                </div>

                <div class="mb-2">
                    <label>Foto do Pet</label>
                    <input type="file" wire:model="image" class="w-full border px-2 py-1 rounded">
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 w-full hover:bg-blue-600">Cadastrar Pet</button>
            </form>
        </div>
    </div>
</div>
