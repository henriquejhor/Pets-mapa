<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
    <h1 class="text-2xl font-bold mb-4">PETS CADASTRADOS</h1>

    @if($selectedPet)
        {{-- Mostra apenas o pet selecionado --}}
        <div class="p-4 border rounded shadow mb-4">
            <h2 class="font-semibold">{{ $selectedPet->name ?? 'Sem nome' }}</h2>
            <p><strong>Tipo:</strong> {{ $selectedPet->type }}</p>
            <p><strong>Descrição:</strong> {{ $selectedPet->description ?? 'Não informada' }}</p>
            <p><strong>Telefone:</strong> {{ $selectedPet->telefone }}</p>
            <p><strong>Cidade:</strong> {{ $selectedPet->city }}</p>

            @if($selectedPet->image_path)
                <img src="{{ asset('storage/'.$selectedPet->image_path) }}"
                     alt="Foto do Pet"
                     class="mt-2 rounded w-40 h-40 object-cover">
            @endif

            <a href="{{ route('pets.index') }}" class="text-blue-500 mt-2 inline-block">Voltar à lista completa</a>
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
                    <div class="p-4 border rounded shadow">
                        <h2 class="font-semibold">{{ $pet->name ?? 'Sem nome' }}</h2>
                        <p><strong>Tipo:</strong> {{ $pet->type }}</p>
                        <p><strong>Descrição:</strong> {{ $pet->description ?? 'Não informada' }}</p>

                        @if($pet->image_path)
                            <img src="{{ asset('storage/'.$pet->image_path) }}"
                                 alt="Foto do Pet"
                                 class="mt-2 rounded w-40 h-40 object-cover">
                        @endif

                        <a href="{{ url('/pets/' . $pet->id) }}" class="text-blue-500 mt-2 inline-block">
                            Clique para mais informações
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>