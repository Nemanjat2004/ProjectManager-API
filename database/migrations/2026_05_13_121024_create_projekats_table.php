<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projekti', function (Blueprint $table) {
            $table->id();
            $table->string('naziv');
            $table->text('opis')->nullable();
            $table->string('klijent')->nullable();
            $table->enum('status', ['u_planu', 'aktivan', 'zavrsen'])->default('u_planu');
            // Koji admin drži ovaj projekat
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // PIVOT TABELA: Spaja radnike i projekte (Više na više)
        Schema::create('projekat_radnik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projekat_id')->constrained('projekti')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projekats');
    }
};
