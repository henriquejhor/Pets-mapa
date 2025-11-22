<div class="max-w-4xl mx-auto p-6 bg-[#047857] shadow rounded">

    <h1 class="text-2xl font-bold mb-4 text-center text-white">Minhas Publicações</h1>

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
        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
            <i class="fa-solid fa-paw text-4xl text-gray-400 mb-3"></i>
            <p class="text-gray-500 text-lg">Você ainda não publicou nenhum pet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            @foreach($pets as $pet)
                
                <!-- CARD COM NOVO DESIGN -->
                <div class="bg-white block max-w-sm border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 mx-auto w-full flex flex-col">
                    
                    <!-- 1. IMAGEM (Com LogoNova se vazio) -->
                    <div class="h-56 overflow-hidden rounded-t-lg border-b border-gray-100 relative group">
                        @if($pet->image_path)
                            <img src="{{ asset('storage/'.$pet->image_path) }}" 
                                 alt="Foto do Pet" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <img src="{{ asset('LogoNova.png') }}" 
                                 alt="Foto Padrão" 
                                 class="w-full h-full object-cover bg-gray-50 p-4 transition-transform duration-500 group-hover:scale-105">
                        @endif
                    </div>

                    <hr class="border-black border-t-2 w-full">

                    <!-- CONTEÚDO DO CARD -->
                    <div class="p-6 flex flex-col flex-grow text-center">
                        
                        <!-- 2. NOME -->
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 truncate">
                            {{ $pet->name ?? 'Sem nome' }}
                        </h5>

                        <!-- 3. BADGE / STATUS -->
                        <div class="mb-4">
                            <span class="inline-flex items-center border text-xs font-medium px-2.5 py-1 rounded-full
                                {{ $pet->type === 'perdido' 
                                    ? 'bg-red-100 border-red-200 text-red-800' 
                                    : 'bg-gray-100 border-gray-300 text-gray-800' 
                                }}">
                                
                                <svg class="w-3 h-3 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2c1.657 0 3 1.343 3 3s-1.343 3-3 3-3-1.343-3-3 1.343-3 3-3zm7 3c1.657 0 3 1.343 3 3s-1.343 3-3 3-3-1.343-3-3 1.343-3 3-3zM5 5c1.657 0 3 1.343 3 3s-1.343 3-3 3-3-1.343-3-3 1.343-3 3-3zM12 10c3.314 0 6 2.686 6 6s-2.686 6-6 6-6-2.686-6-6 2.686-6 6-6z"/>
                                </svg>
                                {{ ucfirst($pet->type) }}
                            </span>
                        </div>

                        <!-- Detalhes Rápidos -->
                        <div class="flex justify-center gap-4 text-sm text-gray-500 mb-4">
                            <span class="flex items-center"><i class="fa-solid fa-location-dot mr-1 text-blue-400"></i> {{ $pet->city }}</span>
                            <span class="flex items-center"><i class="fa-solid fa-phone mr-1 text-blue-400"></i> {{ $pet->telefone }}</span>
                        </div>

                        <!-- Descrição -->
                        <p class="mb-6 font-normal text-gray-700 line-clamp-2 flex-grow">
                            {{ $pet->description ?? 'Sem descrição.' }}
                        </p>

                        <!-- 5. BOTÕES DE AÇÃO (EDITAR / EXCLUIR) -->
                        <div class="flex gap-2 justify-center mt-auto pt-4 border-t border-gray-100">
                            
                            <!-- Botão EDITAR (Azul) -->
                            <button wire:click="edit({{ $pet->id }})" 
                                    class="inline-flex items-center text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 focus:outline-none transition-colors">
                                <i class="fa-solid fa-pen-to-square mr-2"></i>
                                Editar
                            </button>

                            <!-- Botão EXCLUIR (Vermelho) -->
                            <button wire:click="confirmDelete({{ $pet->id }})" 
                                    class="bg-red-600 text-white py-1 px-3 rounded-md hover:bg-red-700 font-semibold">
                                <i class="fa-solid fa-trash text-white pt-1"></i>
                                Excluir
                            </button>
                        </div>

                    </div>
                </div>
                <!-- FIM DO CARD -->

            @endforeach
        </div>
    @endif

    {{-- MODAL DE EDIÇÃO --}}
    @if($showEditModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h2 class="text-xl font-bold text-gray-800">Editar Publicação</h2>
                    <button wire:click="$set('showEditModal', false)" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nome do Pet</label>
                        <input type="text" wire:model="name" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo</label>
                        <select wire:model="type" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">Selecione</option>
                            <option value="perdido">Perdido</option>
                            <option value="encontrado">Encontrado</option> 
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cidade</label>
                        <input type="text" wire:model="city" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Telefone</label>
                        <input type="text" wire:model="telefone" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descrição</label>
                        <textarea wire:model="description" maxlength="100" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                    </div>
                    
                    @if($image_preview)
                        <div>
                            <span class="block text-sm font-medium text-gray-700 mb-1">Imagem atual:</span>
                            <img src="{{ $image_preview }}" class="w-24 h-24 object-cover rounded shadow-sm border">
                        </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Trocar Imagem</label>
                        <input type="file" wire:model="image" class="w-full mt-2 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div class="mt-6 flex justify-end gap-2 pt-4 border-t">
                        <button wire:click="$set('showEditModal', false)" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">Cancelar</button>
                        <button wire:click="update" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Salvar Alterações</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL DE exclusão --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
            x-transition.opacity>
            
            <div class="bg-white rounded-lg shadow-xl w-full max-w-sm p-6 text-center">
                
                <!-- Ícone -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="text-lg font-bold text-gray-900 mb-2">Tem certeza?</h3>
                <p class="text-gray-500 text-sm mb-6">Essa ação não pode ser desfeita.</p>

                <div class="flex justify-center gap-3">
                    <!-- Botão Cancelar -->
                    <button wire:click="$set('showDeleteModal', false)" 
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md font-medium w-full">
                        Cancelar
                    </button>
                    
                    <!-- Botão Confirmar -->
                    <button wire:click="delete" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium w-full shadow-md">
                        Sim, Excluir
                    </button>
                </div>
            </div>
        </div>
    @endif
    
</div>