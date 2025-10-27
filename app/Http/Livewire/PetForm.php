<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;

class PetForm extends Component
{
    use WithFileUploads;

    public $name;
    public $type;
    public $description;
    public $telefone;
    public $image;
    public $latitude;
    public $longitude;
    public $city;
    public $showModal = false; //Controla se abre o modal para cadastrar pet

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

    //Listener para abrir modal
    protected $listeners = [
    'openPetModal' => 'openPetModal'
    ];


    public function openPetModal($lat, $lng){
    $this->reset(['name','type','description','telefone','city','latitude','longitude','image']);
    $this->latitude = $lat;
    $this->longitude = $lng;
    $this->showModal = true;
    }


    public function submit()
    {
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

        // Reset no formulário
        $this->reset(['name','type','description','telefone','city','latitude','longitude','image']);
        $this->showModal = false; //fecha o modal
        session()->flash('message', 'Pet cadastrado com sucesso!');
    }


    public function render()
    {
        return view('livewire.pet-form');
    }
}
