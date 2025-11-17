<div class="max-w-4xl mx-auto p-6 bg-[#01A58D] shadow rounded">

    <h1 class="text-2xl font-bold mb-4 text-white">Minhas Publicações</h1>

    @if (session()->has('success') || session()->has('message'))
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="display: none;" class="flex w-full overflow-hidden bg-white rounded-lg shadow-md dark:bg-gray-800 mb-4"
        >
            <div class="flex items-center justify-center w-12 bg-emerald-500">
                <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z" />
                </svg>
            </div>
            <div class="px-4 py-2 -mx-3">
                <div class="mx-3">
                    <span class="font-semibold text-emerald-500 dark:text-emerald-400">Sucesso</span>
                    <p class="text-sm text-gray-600 dark:text-gray-200">{{ session('success') ?? session('message') }}</p>
                </div>
            </div>
        </div>
    @endif
    @if($pets->isEmpty())
        <p>Você ainda não publicou nenhum pet.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($pets as $pet)
                <div class="text-black p-4 border-4 border-[#8AC7CF] rounded shadow flex items-start justify-between bg-[#6BAFB7]">
                    
                    <div class="flex flex-col bg-[#8AC7CF] py-2 px-2 rounded-lg flex-1">
                        
                        <h2 class="font-semibold text-2xl mb-2">{{ $pet->name ?? 'Sem nome'}}</h2>
                        <p class="mb-1"><strong>Tipo:</strong> {{ $pet->type }}</p>
                        <p class="mb-1"><strong>Cidade:</strong> {{ $pet->city }}</p>
                        <p class="mb-4"><strong>Telefone:</strong> {{ $pet->telefone }}</p> 
                        
                        <div class="flex gap-3">
                            <button wire:click="edit({{ $pet->id }})" 
                                    class="bg-blue-600 text-white py-1 px-3 rounded-md hover:bg-blue-700 font-semibold">
                                    <i class="fa-solid fa-pen-to-square text-white pt-1"></i>
                                Editar
                            </button>
                            <button wire:click="delete({{ $pet->id }})" 
                                    class="bg-red-600 text-white py-1 px-3 rounded-md hover:bg-red-700 font-semibold">
                                    <i class="fa-solid fa-trash text-white pt-1"></i>
                                Excluir
                            </button>
                        </div>
                    </div>

                    @if($pet->image_path)
                        <img src="{{ asset('storage/'.$pet->image_path) }}"
                             alt="Foto do Pet"
                             class="rounded w-40 h-40 object-cover ml-4 border-8 border-[#8AC7CF] flex-shrink-0">
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    
    {{-- Modal de edição --}}
    @if($showEditModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded p-6 w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">Editar Publicação</h2>

                <div class="space-y-3">

                    {{-- Nome --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Nome</label>
                        <input type="text" wire:model="name" class="w-full border rounded p-2">
                    </div>

                    {{-- Tipo --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Tipo</label>
                        <select wire:model="type" class="w-full border rounded p-2">
                            <option value="">Selecione tipo</option>
                            <option value="perdido">Perdido</option>
                            <option value="encontrado">Encontrado</option>
                        </select>
                    </div>

                    {{-- Cidade --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Cidade</label>
                        <input type="text" wire:model="city" class="w-full border rounded p-2">
                    </div>

                    {{-- Telefone --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Telefone</label>
                        <input type="text" wire:model="telefone" class="w-full border rounded p-2">
                    </div>

                    {{-- Descrição --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Descrição</label>
                        <textarea wire:model="description" maxlength="100"
                                class="w-full border rounded p-2"></textarea>
                    </div>

                    {{-- Imagem atual --}}
                    @if($image_preview)
                        <div>
                            <label class="block text-sm font-semibold mb-1">Imagem atual</label>
                            <img src="{{ $image_preview }}" class="w-24 h-24 object-cover mt-1 rounded">
                        </div>
                    @endif

                    {{-- Upload de nova imagem --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Nova imagem</label>
                        <input type="file" wire:model="image" class="w-full">
                    </div>

                    {{-- Preview da nova imagem --}}
                    @if ($image)
                        <div class="mt-2">
                            <span>Nova imagem:</span>
                            <img src="{{ $image->temporaryUrl() }}" class="w-24 h-24 object-cover mt-1 rounded">
                        </div>
                    @endif

                    {{-- Botões --}}
                    <div class="mt-4 flex justify-end gap-2">
                        <button wire:click="$set('showEditModal', false)"
                                class="bg-gray-300 px-3 py-1 rounded">Cancelar</button>
                        <button wire:click="update"
                                class="bg-blue-600 text-white px-3 py-1 rounded">Salvar</button>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>