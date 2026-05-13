<?php
namespace Database\Seeders;

use App\Models\Projekat;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        $admin = User::create([
            'name'        => 'Ana Admin',
            'username'    => 'ana',
            'email'       => 'ana@firma.com',
            'password'    => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role'        => 'admin',
            'max_radnika' => 5,
        ]);

        // 2. Radnici
        $marko = User::create([
            'name'          => 'Marko Radnik',
            'username'      => 'marko',
            'email'         => 'marko@firma.com',
            'password'      => \Illuminate\Support\Facades\Hash::make('sifra123'),
            'role'          => 'radnik',
            'nadredjeni_id' => $admin->id,
        ]);

        $jovan = User::create([
            'name'          => 'Jovan Radnik',
            'username'      => 'jovan',
            'email'         => 'jovan@firma.com',
            'password'      => \Illuminate\Support\Facades\Hash::make('sifra123'),
            'role'          => 'radnik',
            'nadredjeni_id' => $admin->id,
        ]);

        $sara = User::create([
            'name'          => 'Sara Radnik',
            'username'      => 'sara',
            'email'         => 'sara@firma.com',
            'password'      => \Illuminate\Support\Facades\Hash::make('sifra123'),
            'role'          => 'radnik',
            'nadredjeni_id' => $admin->id,
        ]);

        // 3. Projekat 1 — Marko i Jovan
        $p1 = Projekat::create([
            'naziv'    => 'Nexus Platforma',
            'opis'     => 'Razvoj centralne platforme za upravljanje korisnicima i SSO autentifikacijom.',
            'klijent'  => 'TechCorp d.o.o.',
            'status'   => 'aktivan',
            'admin_id' => $admin->id,
        ]);
        $p1->radnici()->attach([$marko->id, $jovan->id]);

        // 4. Projekat 2 — Jovan i Sara
        $p2 = Projekat::create([
            'naziv'    => 'Aurora CRM',
            'opis'     => 'CRM rješenje za praćenje klijenata i prodajnih tokova.',
            'klijent'  => 'SalesPro d.o.o.',
            'status'   => 'u_planu',
            'admin_id' => $admin->id,
        ]);
        $p2->radnici()->attach([$jovan->id, $sara->id]);
    }
}
