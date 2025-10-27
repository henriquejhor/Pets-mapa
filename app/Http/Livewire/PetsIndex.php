<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Pet;

class PetsIndex extends Component
{
    public $selectedPet; // pet selecionado
    public $search = '';       // variável para pesquisa

    // Recebe ID opcional via rota
    public function mount($id = null)
    {
        $this->selectedPet = $id ? Pet::find($id) : null;
    }

    public function render()
    {
        $pets = Pet::query()
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('city', 'like', '%'.$this->search.'%')
                      ->orWhere('type', 'like', '%'.$this->search.'%');
                });
            })
            ->latest()
            ->get();

        return view('livewire.pets-index', compact('pets'));
    }
}
