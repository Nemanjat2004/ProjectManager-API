<?php
namespace App\Http\Controllers;

use App\Models\Projekat;
use App\Models\User;
use Illuminate\Http\Request;

class RadnikController extends Controller
{
    // Funkcija koja vraća samo radnike ulogovanog admina
    public function getMojiRadnici(Request $request)
    {
        $admin = $request->user(); // DODAJ OVO — nedostajalo je

        $radnici = User::where('nadredjeni_id', $admin->id)
            ->select('id', 'name', 'role', 'email')
            ->get();
        return response()->json($radnici);
    }

    public function dodajRadnika(Request $request)
    {
        $admin = $request->user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        // 1. Provera kvote: Da li je Admin dostigao maksimum radnika?
        $trenutnoRadnika = User::where('nadredjeni_id', $admin->id)->count();

        if ($admin->max_radnika !== null && $trenutnoRadnika >= $admin->max_radnika) {
            return response()->json([
                'poruka' => "Dostigli ste maksimalan broj radnika ({$admin->max_radnika}).",
            ], 403);
        }

        // 2. Validacija (sva polja moraju biti uneta i email/username moraju biti jedinstveni)

        // 3. Kreiranje radnika u bazi
        $noviRadnik = User::create([
            'name'          => $request->name,
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => \Illuminate\Support\Facades\Hash::make($request->password),
            'role'          => 'radnik',
            'nadredjeni_id' => $admin->id,
        ]);

        return response()->json([
            'poruka' => 'Radnik je uspešno dodat!',
            'radnik' => $noviRadnik,
        ]);
    }

    public function getMojiProjekti(Request $request)
    {
        $admin = $request->user();

        $projekti = Projekat::where('admin_id', $admin->id)
            ->withCount('radnici') // Automatski dodaje "radnici_count" u odgovor
            ->get();

        return response()->json($projekti);
    }
}
