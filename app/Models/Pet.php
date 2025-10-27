<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    // Campos que podem ser preenchidos via mass assignment
    protected $fillable = [
        'user_id', 'name', 'type', 'description', 'telefone', 'image_path', 'latitude', 'longitude', 'city', 'expires_at'
    ];

    // Relacionamento: cada pet pertence a um usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
