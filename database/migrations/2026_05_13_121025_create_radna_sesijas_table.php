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
        Schema::create('radne_sesije', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('projekat_id')->constrained('projekti')->onDelete('cascade');
            $table->timestamp('vreme_pocetka')->useCurrent();
            $table->timestamp('vreme_kraja')->nullable();
            $table->enum('status', ['u_toku', 'pauziran', 'zavrsen'])->default('u_toku');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radna_sesijas');
    }
};
