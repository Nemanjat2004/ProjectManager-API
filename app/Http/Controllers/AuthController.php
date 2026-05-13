<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Funkcija koja obrađuje prijavu
    public function login(Request $request)
    {
        // 1. Validacija
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'role'     => 'required',
        ]);

        // 2. Traženje korisnika
        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        // 3. Provera šifre i postojanja
        if (! $user || ! Hash::check($request->password, $user->password) || $user->role !== $request->role) {
            return response()->json(['poruka' => 'Pogrešno korisničko ime ili lozinka.'], 401);
        }

        // 5. KREIRANJE TOKENA: Ako je sve u redu, generišemo token
        $token = $user->createToken('DesktopAppToken')->plainTextToken;

        // 6. Vraćanje odgovora sa tokenom
        return response()->json([
            'status'   => 'uspeh',
            'poruka'   => 'Uspešna prijava!',
            'token'    => $token, // <-- ŠALJEMO TOKEN U C#
            'korisnik' => $user,
        ]);
    }
}
