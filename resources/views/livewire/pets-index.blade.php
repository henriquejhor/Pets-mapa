<div class="max-w-4xl mx-auto p-6 bg-[#1B3B5A] shadow rounded">
    <h1 class="text-2xl text-white text-center font-bold mb-4">PETS PERDIDOS</h1>

    @if($selectedPet)
        <div class="bg-white border border-gray-200 rounded-lg shadow-xl overflow-hidden flex flex-col md:flex-row mb-8">
        
        <!-- Lado Esquerdo: Informações (Ocupa o espaço restante) -->
        <!-- 'order-2 md:order-1' faz com que este bloco fique EM BAIXO no celular e NA ESQUERDA no PC -->
        <div class="p-8 flex flex-col justify-between flex-1 order-2 md:order-1">
            
            <div>
                <!-- Nome e Badge de Tipo -->
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <h2 class="text-3xl font-bold text-gray-800">
                        {{ $selectedPet->name ?? 'Sem nome' }}
                    </h2>
                    
                    <!-- Badge de Status -->
                    <span class="inline-flex items-center border text-sm font-medium px-3 py-1.5 rounded-full
                        {{ $selectedPet->type === 'perdido' 
                            ? 'bg-red-100 border-red-200 text-red-800' 
                            : 'bg-gray-100 border-gray-300 text-gray-800' 
                        }}">
                        <!-- Ícone de Patinha -->
                        <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6.5 5c1.93 0 3.5 1.57 3.5 3.5S8.43 12 6.5 12 3 10.43 3 8.5 4.57 5 6.5 5zM17.5 5c1.93 0 3.5 1.57 3.5 3.5S19.43 12 17.5 12s-3.5-1.57-3.5-3.5 1.57-3.5 3.5-3.5zM12 2c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zM12 10.5c3.5 0 7 1.5 7 6 0 4-3 6.5-7 6.5s-7-2.5-7-6.5c0-4.5 3.5-6 7-6z"/>
                        </svg>
                        {{ ucfirst($selectedPet->type) }}
                    </span>
                </div>

                <!-- Detalhes (Cidade e Telefone) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 text-gray-600">
                    <div class="flex items-center">
                        <i class="fa-solid fa-location-dot text-blue-500 text-xl w-8 text-center mr-2"></i>
                        <div>
                            <span class="block text-xs uppercase tracking-wide text-black font-bold">Cidade</span>
                            <span class="text-lg font-medium">{{ $selectedPet->city }}</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fa-solid fa-phone text-blue-500 text-xl w-8 text-center mr-2"></i>
                        <div>
                            <span class="block text-xs uppercase tracking-wide text-black font-bold">Telefone</span>
                            <span class="text-lg font-medium">{{ $selectedPet->telefone }}</span>
                        </div>
                    </div>
                </div>

                <!-- Descrição -->
                <div class="mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <span class="block text-xs uppercase tracking-wide text-black font-bold mb-2">
                        <i class="fa-solid fa-file-lines mr-1"></i> Descrição
                    </span>
                    <p class="text-gray-700 leading-relaxed text-lg">
                        {{ $selectedPet->description ?? 'Nenhuma descrição informada.' }}
                    </p>
                </div>
            </div>

            <!-- Botão Voltar (Azul) -->
            <div>
                <a href="{{ route('pets.index') }}" 
                   class="inline-flex items-center justify-center w-full sm:w-auto text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-base px-6 py-3 transition-colors duration-200 shadow-md">
                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    Voltar à Lista Completa
                </a>
            </div>
        </div>

        <!-- Lado Direito: Imagem (Grande e Responsiva) -->
        <!-- 'order-1 md:order-2' faz com que este bloco fique NO TOPO no celular e NA DIREITA no PC -->
        <div class="w-full md:w-2/5 h-64 md:h-auto relative order-1 md:order-2 bg-gray-100 border-b md:border-b-0 md:border-l border-gray-200">
            @if($selectedPet->image_path)
                <img src="{{ asset('storage/'.$selectedPet->image_path) }}" 
                     alt="Foto do Pet" 
                     class="absolute inset-0 w-full h-full object-cover">
            @else
                <img src="{{ asset('LogoNova.png') }}" 
                     alt="Foto Padrão" 
                     class="absolute inset-0 w-full h-full object-cover p-8 opacity-70">
            @endif
        </div>

    </div>
    @else
       {{-- Barra de Pesquisa --}}
        <div class="mb-4">
            <input type="text" 
                   wire:model.live="search" {{-- .live para atualização em tempo real --}}
                   placeholder="Buscar por nome, cidade ou tipo"
                   class="border rounded-xl p-2 w-full">
        </div>
        
        {{-- Mensagem quando não encontrar resultados --}}
        @if($pets->isEmpty() && $search)
            <div class="text-center p-4 text-gray-500">
                Nenhum pet encontrado para "{{ $search }}"
            </div>
        @else
            {{-- Lista todos os pets --}}
            <<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
        @foreach($pets as $pet)
                <!-- Container do Card -->
                <div class="bg-white block max-w-sm border border-gray-200 rounded-lg shadow hover:shadow-md transition-shadow duration-300 mx-auto w-full">
                    
                    <!-- 1. LÓGICA DA IMAGEM (Linkando para o detalhe) -->
                    <a href="{{ url('/pets/' . $pet->id) }}">
                        @if($pet->image_path)
                            <img src="{{ asset('storage/'.$pet->image_path) }}" 
                                alt="Foto do Pet" 
                                class="rounded-t-lg w-full h-64 object-cover">
                        @else
                            <img src="{{ asset('LogoNova.png') }}" 
                                alt="Foto Padrão" 
                                class="rounded-t-lg w-full h-64 object-cover bg-gray-100">
                        @endif
                    </a>

                    <hr class="border-black border-t-2 w-full">

                    <div class="p-6 text-center">
                        
                        <!-- 2. NOME DO PET -->
                        <h2 class="text-2xl font-bold text-gray-800 mb-3 truncate">
                            {{ $pet->name ?? 'Sem nome' }}
                        </h2>

                        <!-- 3. BADGE DE STATUS  -->
                        <!-- Lógica: Vermelho para Perdido, Cinza/Preto para Encontrado -->
                        <span class="inline-flex items-center border text-sm font-medium px-3 py-1.5 rounded-full mb-4
                            {{ $pet->type === 'perdido' 
                                ? 'bg-red-100 border-red-200 text-red-800' 
                                : 'bg-gray-100 border-gray-300 text-gray-800' 
                            }}">
                            
                            <!-- Ícone do Badge -->
                            <svg class="w-4 h-4 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6.5 5c1.93 0 3.5 1.57 3.5 3.5S8.43 12 6.5 12 3 10.43 3 8.5 4.57 5 6.5 5zM17.5 5c1.93 0 3.5 1.57 3.5 3.5S19.43 12 17.5 12s-3.5-1.57-3.5-3.5 1.57-3.5 3.5-3.5zM12 2c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zM12 10.5c3.5 0 7 1.5 7 6 0 4-3 6.5-7 6.5s-7-2.5-7-6.5c0-4.5 3.5-6 7-6z"/>
                            </svg>
                            
                            {{ ucfirst($pet->type) }} <!-- Exibe "Perdido" ou "Encontrado" -->
                        </span>

                        <!-- 4. DESCRIÇÃO -->
                        <a href="{{ url('/pets/' . $pet->id) }}">
                            <h5 class="mb-4 text-lg font-normal tracking-tight text-gray-600 line-clamp-2">
                                {{ $pet->description ?? 'Sem descrição disponível.' }}
                            </h5>
                        </a>

                        <!-- 5. BOTÃO "VER MAIS" -->
                        <a href="{{ url('/pets/' . $pet->id) }}" class="inline-flex items-center text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200">
                            Ver Mais
                            <svg class="w-4 h-4 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                                <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                        </a>

                    </div>
                </div>
            @endforeach
        </div>
        @endif
    @endif
</div>