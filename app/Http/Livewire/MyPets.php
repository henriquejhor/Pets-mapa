<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;

class MyPets extends Component
{
    use WithFileUploads;

    public $pets;
    public $editingPet = null; //Pet que está sendo editado
    public $name, $type, $city, $description, $telefone, $image, $image_preview;
    
    public $showEditModal = false;
    
    public function render()
    {
        $this->pets = Pet::where('user_id', Auth::id())->latest()->get();
        return view('livewire.my-pets');
    }

    // MODAL E EDITAR CAMPOS
    public function edit($id)
    {
        $this->editingPet = Pet::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$this->editingPet) return;

        $this->name = $this->editingPet->name;
        $this->type = $this->editingPet->type;
        $this->city = $this->editingPet->city;
        $this->description = $this->editingPet->description;
        $this->telefone = $this->editingPet->telefone;
        $this->image_preview = $this->editingPet->image_path ? asset('storage/'.$this->editingPet->image_path) : null;

        $this->showEditModal = true;
    }

    // SALVAR ALTERAÇÕES
    public function update()
    {
        if (!$this->editingPet) return;

        $this->editingPet->name = $this->name;
        $this->editingPet->type = $this->type;
        $this->editingPet->city = $this->city;
        $this->editingPet->description = $this->description;
        $this->editingPet->telefone = $this->telefone;

        if ($this->image) {
            // Apaga imagem antiga se existir
            if ($this->editingPet->image_path && file_exists(storage_path('app/public/'.$this->editingPet->image_path))) {
                unlink(storage_path('app/public/'.$this->editingPet->image_path));
            }

            $this->editingPet->image_path = $this->image->store('pets', 'public');
        }

        $this->editingPet->save();

        // Fecha o modal
        $this->showEditModal = false;

        // Limpa o formulário
        $this->reset(['editingPet', 'name', 'type', 'city', 'description', 'telefone', 'image', 'image_preview']);

        // Mensagem de sucesso
        session()->flash('message', 'Publicação atualizada com sucesso!');
    }

    // EXCLUIR PET
    public function delete($id)
    {
        $pet = Pet::where('id', $id)->where('user_id', Auth::id())->first();
        if ($pet) {
            if ($pet->image_path && file_exists(storage_path('app/public/'.$pet->image_path))) {
                unlink(storage_path('app/public/'.$pet->image_path));
            }
            $pet->delete();
            session()->flash('message', 'Publicação Excluída com Sucesso!');
        }
    }
}
