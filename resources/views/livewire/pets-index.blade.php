<div class="max-w-4xl mx-auto p-6 bg-[#F7715D] shadow rounded">
    <h1 class="text-2xl text-white font-bold mb-4">PETS PERDIDOS</h1>

    @if($selectedPet)
        {{-- Mostra apenas o pet selecionado --}}
        <div class="text-black text-semibold text-2xl p-4 border-4 rounded shadow mb-4 flex items-start justify-between bg-[#D1614F]">
         <!-- Lado esquerdo: informações -->
         <div class="flex flex-col space-y-1 bg-[#8AC7CF] py-2 px-2 rounded-lg flex-1 min-w-0 break-words">
            <h2 class="font-semibold text-2xl flex items-center space-x-2 mb-2">
              <i class="fa-solid fa-tag text-white"></i>
              <span>{{ $selectedPet->name ?? 'Sem nome' }}</span>
          </h2>
            <p class="flex items-center space-x-2 mb-2">
              <i class="fa-solid fa-magnifying-glass text-white"></i>
              <span>Tipo: {{ $selectedPet->type }}</span>
            </p>
            <p class="flex items-start space-x-2 mb-2 ">
              <i class="fa-solid fa-file-lines text-white pt-1"></i>
              <span class="min-w-0 break-all">Descrição: {{ $selectedPet->description ?? 'Não informada' }}</span>
            </p>
            <p class="flex items-center space-x-2 mb-2">
              <i class="fa-solid fa-phone text-white"></i>
              <span>Telefone: {{ $selectedPet->telefone }}</span>
            </p>
            <p class="flex items-center space-x-2 mb-2">
              <i class="fa-solid fa-location-dot text-white"></i>
              <span>Cidade: {{ $selectedPet->city }}</span>
            </p>
            <a href="{{ route('pets.index') }}" 
             class="text-[#01594F] no-underline pt-8 flex items-center justify-center space-x-2">
              <i class="fa-solid fa-arrow-left text-white"></i>
              <span class="underline">VOLTAR À LISTA COMPLETA</span>
            </a>
         </div>

            @if($selectedPet->image_path)
                <img src="{{ asset('storage/'.$selectedPet->image_path) }}"
                     alt="Foto do Pet"
                     class="rounded w-64 h-64 object-cover ml-6 border-8 border-[#8AC7CF]">
            @endif

        </div>
    @else
       {{-- Barra de Pesquisa --}}
        <div class="mb-4">
            <input type="text" 
                   wire:model.live="search" {{-- Adicione .live para atualização em tempo real --}}
                   placeholder="Buscar por nome, cidade ou tipo"
                   class="border rounded p-2 w-full">
        </div>
        
        {{-- Mensagem quando não encontrar resultados --}}
        @if($pets->isEmpty() && $search)
            <div class="text-center p-4 text-gray-500">
                Nenhum pet encontrado para "{{ $search }}"
            </div>
        @else
            {{-- Lista todos os pets --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($pets as $pet)
                    <div class="text-black p-4 border-4 rounded shadow flex items-start justify-between bg-[#D1614F]">
                        <div class="flex flex-col space-y-1 bg-[#8AC7CF] py-2 px-2 rounded-lg flex-1 min-w-0 break-words">
                            <h2 class="font-semibold">
                                <i class="fa-solid fa-tag text-white"></i>
                                {{ $pet->name ?? 'Sem nome' }}
                            </h2>
                            <p>
                                <i class="fa-solid fa-magnifying-glass text-white"></i>
                                <strong>Tipo:</strong> 
                                {{ $pet->type }}
                            </p>
                            <p>
                                <i class="fa-solid fa-file-lines text-white pt-1"></i>
                                <strong>Descrição:</strong> 
                                {{ $pet->description ?? 'Não informada' }}
                            </p>

                            <a href="{{ url('/pets/' . $pet->id) }}" 
                                class="text-[#01594F] text-center mt-2 inline-block">
                                <i class="fa-solid fa-eye text-white pt-1"></i>
                                Ver Mais
                            </a>
                        </div>

                        @if($pet->image_path)
                            <img src="{{ asset('storage/'.$pet->image_path) }}"
                                 alt="Foto do Pet"
                                 class="mt-2 rounded w-40 h-40 object-cover ml-4 border-8 border-[#8AC7CF] flex-shrink-0">
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>