<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;

class PetsMap extends Component
{
    use WithFileUploads;

    public $pets;
    public $name;
    public $type;
    public $description;
    public $telefone;
    public $image;
    public $latitude;
    public $longitude;
    public $city;
    public $showModal = false;

    protected $rules = [
        'name' => 'nullable|string|max:255',
        'type' => 'required|in:perdido,encontrado',
        'description' => 'nullable|string',
        'telefone' => 'required|string|max:16',
        'image' => 'nullable|image|max:2048',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'city' => 'nullable|string|max:100',
    ];

    public function mount()
    {
        $this->pets = Pet::all();
    }

    public function openPetModal($lat, $lng)
    {
        // "Porteiro" - Manda para o login com a mensagem de 'info'
        
        if (!Auth::check()) {
            session()->flash('info', 'Você precisa estar logado para cadastrar um pet!');
            return redirect()->route('login'); 
        } 
        
        // Se estiver logado, abre o modal
        $this->reset(['name','type','description','telefone','city','latitude','longitude','image']);
        $this->latitude = $lat;
        $this->longitude = $lng;
        $this->showModal = true;
    }

    public function submit()
    {
        // Checagem de segurança 
        if (!Auth::check()) {
            session()->flash('error', 'Sua sessão expirou. Por favor, faça login novamente.');
            return redirect()->route('login');
        }

        // Validação 
        $this->validate();

        $imagePath = $this->image ? $this->image->store('pets', 'public') : null;

        Pet::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'telefone' => $this->telefone,
            'city' => $this->city,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'image_path' => $imagePath,
            'expires_at' => now()->addDays(30),
        ]);
        
        // ***** A MUDANÇA ESTÁ AQUI *****

        // SESSION
        session()->flash('message', 'Pet cadastrado com sucesso!');
        
        // RECAREGAR PETS
        $this->pets = Pet::all();
        
        // LIMPAR CAMPOS
        $this->reset(['name','type','description','telefone','city','latitude','longitude','image']);
    }

    public function render()
    {
        return view('livewire.pets-map');
    }
}