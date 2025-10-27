<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id(); // ID do pet
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // dono do pet
            $table->string('name')->nullable();           // nome do pet
            $table->enum('type', ['perdido', 'encontrado']); // status do pet
            $table->text('description')->nullable();     // detalhes adicionais
            $table->string('telefone', 16);              // telefone obrigatório
            $table->string('image_path')->nullable();    // caminho da foto
            $table->decimal('latitude', 10, 7);          // latitude
            $table->decimal('longitude', 10, 7);         // longitude
            $table->string('city')->nullable();          // cidade/região (opcional)
            $table->timestamp('expires_at');             // expira após 30 dias
            $table->timestamps();                        // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
