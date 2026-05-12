<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Pravimo jednog probnog Radnika
        \App\Models\User::factory()->create([
            'name'     => 'Marko Radnik',
            'username' => 'marko',
            'email'    => 'marko@firma.com',
            'password' => \Illuminate\Support\Facades\Hash::make('sifra123'),
            'role'     => 'radnik',
        ]);

        // Pravimo jednog probnog Admina
        \App\Models\User::factory()->create([
            'name'     => 'Ana Admin',
            'username' => 'ana',
            'email'    => 'ana@firma.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role'     => 'admin',
        ]);
    }
}
