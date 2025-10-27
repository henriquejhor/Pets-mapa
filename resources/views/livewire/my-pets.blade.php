<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">

    <h1 class="text-2xl font-bold mb-4">Minhas Publicações</h1>

    @if(session('message'))
        <div class="p-2 bg-green-100 text-green-800 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if($pets->isEmpty())
        <p>Você ainda não publicou nenhum pet.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($pets as $pet)
                <div class="p-4 border rounded shadow">
                    <h2 class="font-semibold">{{ $pet->name ?? 'Sem nome'}}</h2>
                    <p><strong>Tipo:</strong> {{ $pet->type }}</p>
                    <p><strong>Cidade:</strong> {{ $pet->city }}</p>
                    <p><strong>Telefone:</strong> {{ $pet->telefone }}</p>

                    @if($pet->image_path)
                            <img src="{{ asset('storage/'.$pet->image_path) }}"
                                class="mt-2 rounded w-40 h-40 object-cover"
                                alt="Foto do Pet">
                    @endif

                    <div class="mt-2 flex gap-3">
                        <button wire:click="edit({{ $pet->id }})" class="text-blue-600">Editar</button>
                        <button wire:click="delete({{ $pet->id }})" class="text-red-600">Excluir</button>
                    </div>
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
                    <input type="text" wire:model="name" placeholder="Nome do Pet"
                           class="w-full border rounded p-2">
                    
                    <select wire:model="type" class="w-full border rounded p-2">
                        <option value="">Selecione tipo</option>
                        <option value="perdido">Perdido</option>
                        <option value="encontrado">Encontrado</option>
                    </select>

                    <input type="text" wire:model="city" placeholder="Cidade"
                           class="w-full border rounded p-2">

                    <input type="text" wire:model="telefone" placeholder="Telefone"
                           class="w-full border rounded p-2">

                    <textarea wire:model="description" placeholder="Descrição"
                              class="w-full border rounded p-2"></textarea>

                    @if($image_preview)
                        <div>
                            <span>Imagem atual:</span>
                            <img src="{{ $image_preview }}" class="w-24 h-24 object-cover mt-1 rounded">
                        </div>
                    @endif

                    <input type="file" wire:model="image" class="w-full mt-2">

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
