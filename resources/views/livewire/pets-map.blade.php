<div>
    <div wire:ignore style="position: relative; z-index: 0;">
        <div id="map" style="height: 500px; width: 100%; border:2px solid red;"></div>
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

            @guest
                // Clique no mapa mostra alerta
                map.on('click', function(e){
                    alert('Você precisa estar logado para cadastrar um pet!');
                });
            @endguest


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
            @if(session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="submit" enctype="multipart/form-data">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome do pet</label>
                        <input type="text" wire:model="name" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                        <select wire:model="type" 
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecione</option>
                            <option value="perdido">Perdido</option>
                            <option value="encontrado">Encontrado</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                        <textarea wire:model="description" 
                                  rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                        <input type="text" wire:model="telefone" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
                        <input type="text" wire:model="city" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                            <input type="text" wire:model="latitude" 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" 
                                   readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                            <input type="text" wire:model="longitude" 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" 
                                   readonly>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto do Pet</label>
                        <input type="file" wire:model="image" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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