<div>
    <div wire:ignore style="position: relative; z-index: 0;">
        <div id="map" style="height: 500px; width: 100%; border:2px solid white;"></div>
    </div>

    @php
        $jsPets = $pets->map(function($p){
            return [
                'id' => $p->id,
                'name' => $p->name,
                'type' => $p->type,
                'latitude' => $p->latitude,
                'longitude' => $p->longitude,
                'city' => $p->city,
                'telefone' => $p->telefone,
                'image' => $p->image_path ? asset('storage/' . $p->image_path) : null,
            ];
        });
    @endphp

    <script>
        setTimeout(function() {
            if (!document.getElementById('map') || typeof L === 'undefined') return;

            const map = L.map('map').setView([-22.8886768, -48.4500518], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            const geocoder = L.Control.geocoder({ defaultMarkGeocode: false }).addTo(map);

            setTimeout(() => {
                const btn = document.querySelector('.leaflet-control-geocoder .leaflet-control-geocoder-icon');
                if(btn) btn.click();
                const input = document.querySelector('.leaflet-control-geocoder form input');
                if(input) input.placeholder = 'Digite sua rua ou cidade';
            }, 500);

            geocoder.on('markgeocode', function(e){
                map.setView(e.geocode.center, 15);
            });

            function escapeHtml(str){
                return String(str || '').replace(/[&<>"'`=\/]/g, function(s){
                    return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'})[s];
                });
            }

            const pets = @json($jsPets);
            pets.forEach(pet => {
                if(!pet.latitude || !pet.longitude) return;
                const iconUrl = pet.image || '/default-pet.png';
                const icon = L.icon({ iconUrl, iconSize:[40,40] });
                const marker = L.marker([parseFloat(pet.latitude), parseFloat(pet.longitude)], { icon }).addTo(map);
                let popupHtml = `<strong>${escapeHtml(pet.name || 'Sem nome')}</strong><br>`;
                if(pet.city) popupHtml += `📍 Cidade: ${escapeHtml(pet.city)}<br>`;
                if(pet.telefone) popupHtml += `📞 Telefone: ${escapeHtml(pet.telefone)}<br>`;
                popupHtml += `<a href="/pets/${pet.id}" class="text-blue-600 underline">Clique para mais informações</a>`;
                marker.bindPopup(popupHtml);
            });

            @auth
                // Clique no mapa abre modal
                map.on('click', function(e){
                    @this.openPetModal(e.latlng.lat, e.latlng.lng);
                });
            @endauth
        }, 500);
    </script>

    
    <div 
        x-data="{ open: @entangle('showModal') }"
        x-show="open"
        x-cloak
        x-transition.opacity.duration.300ms
        @keydown.escape.window="open = false; $wire.showModal = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
        @click="open = false; $wire.showModal = false"
    >
        <div 
            class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto"
            @click.stop
        >
            
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-xl font-bold text-gray-800">Cadastrar Pet</h2>
                <button @click="open=false;$wire.showModal=false"
                        class="text-gray-400 hover:text-gray-600 text-2xl transition-colors duration-200">
                    &times;
                </button>
            </div>

            <div class="p-6">

                @if ($errors->any())
                    <div class="flex w-full overflow-hidden bg-white rounded-lg shadow-md mb-4">
                        <div class="flex items-center justify-center w-12 bg-red-600">
                            <i class="fas fa-exclamation-circle text-white text-xl"></i>
                        </div>
                        <div class="px-4 py-2 -mx-3">
                            <div class="mx-3">
                                <span class="font-semibold text-red-600">Erro de Validação</span>
                                <p class="text-sm text-gray-600">Por favor, corrija os erros abaixo.</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if (session()->has('message'))
                    <div 
                        x-data="{ show: true }"
                        x-show="show"
                        x-init="
                            // 1. Espera 5 segundos
                            setTimeout(() => { 
                                show = false; // 2. Esconde a mensagem
                                $wire.showModal = false; // 3. Fecha o modal
                            }, 3000)
                        "
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        style="display: none;"
                        class="flex w-full overflow-hidden bg-white rounded-lg shadow-md mb-4"
                    >
                        <div class="flex items-center justify-center w-12 bg-emerald-500">
                            <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z" />
                            </svg>
                        </div>
                    
                        <div class="px-4 py-2 -mx-3">
                            <div class="mx-3">
                                <span class="font-semibold text-emerald-500">Sucesso</span>
                                <p class="text-sm text-gray-600">{{ session('message') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <form wire:submit.prevent="submit" enctype="multipart/form-data">
                    <div class="space-y-4 text-black">
                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-1">Nome do pet</label>
                            <input type="text" wire:model="name" 
                                   class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-1">Tipo</label>
                            <select wire:model="type" 
                                    class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror">
                                <option value="">Selecione</option>
                                <option value="perdido">Perdido</option>
                                <option value="encontrado">Encontrado</option>
                            </select>
                            @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-1">Descrição</label>
                            <textarea wire:model="description" 
                                      rows="3"
                                      maxlength="100"
                                      class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-1">Telefone</label>
                            <input type="text" wire:model="telefone" 
                                   class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('telefone') border-red-500 @enderror">
                            @error('telefone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-1">Cidade</label>
                            <input type="text" wire:model="city" 
                                   class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('city') border-red-500 @enderror">
                            @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-1">Latitude</label>
                                <input type="text" wire:model="latitude" 
                                       class="w-full border rounded-md px-3 py-2 bg-gray-100 @error('latitude') border-red-500 @enderror" 
                                       readonly>
                                @error('latitude') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-1">Longitude</label>
                                <input type="text" wire:model="longitude" 
                                       class="w-full border rounded-md px-3 py-2 bg-gray-100 @error('longitude') border-red-500 @enderror" 
                                       readonly>
                                @error('longitude') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-1">Foto do Pet</label>
                            <input type="file" wire:model="image" 
                                   class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('image') border-red-500 @enderror">

                                    @if ($image)
                                        <div class="mt-2">
                                            <span class="block text-sm font-medium text-gray-600 mb-1">Preview:</span>
                                            <img src="{{ $image->temporaryUrl() }}" 
                                                class="w-24 h-24 object-cover rounded-md shadow-lg">
                                        </div>
                                    @endif

                            @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t">
                        <button type="button" 
                                @click="open = false; $wire.showModal = false"
                                class="flex-1 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors duration-200">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors duration-200">
                            Cadastrar Pet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>