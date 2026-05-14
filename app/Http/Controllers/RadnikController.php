<?php
namespace App\Http\Controllers;

use App\Models\Projekat;
use App\Models\User;
use Illuminate\Http\Request;

class RadnikController extends Controller
{
    // Funkcija koja vraća samo radnike ulogovanog admina
    // Funkcija koja vraća samo radnike ulogovanog admina
    public function getMojiRadnici(Request $request)
    {
        $admin = $request->user();

        // Dodato with('projekti') kako bi backend odmah poslao i listu projekata na kojim radnik radi
        $radnici = User::where('nadredjeni_id', $admin->id)
            ->with(['projekti' => function ($query) {
                // Uzimamo samo id i naziv projekta da smanjimo velicinu JSON-a
                $query->select('projekti.id', 'naziv');
            }])
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

    public function getProjektiRadnika(Request $request, $id)
    {
        $admin  = $request->user();
        $radnik = User::where('id', $id)
            ->where('nadredjeni_id', $admin->id)
            ->firstOrFail();

        $projekti = $radnik->projekti()->select('projekti.id', 'naziv')->get();
        return response()->json($projekti);
    }

    public function dodajProjekat(Request $request)
    {
        $admin = $request->user();
        $request->validate([
            'naziv'   => 'required|string|max:255',
            'klijent' => 'required|string|max:255',
            'status'  => 'required|in:u_planu,aktivan,zavrsen',
        ]);

        $projekat = Projekat::create([
            'naziv'    => $request->naziv,
            'opis'     => $request->opis ?? '',
            'klijent'  => $request->klijent,
            'status'   => $request->status,
            'admin_id' => $admin->id,
        ]);

        return response()->json(['poruka' => 'Projekat dodat!', 'projekat' => $projekat]);
    }

    public function obrisiRadnika(Request $request, $id)
    {
        $radnik = User::where('nadredjeni_id', $request->user()->id)->findOrFail($id);
        $radnik->delete();
        return response()->json(['poruka' => 'Radnik je uspešno obrisan.']);
    }

    public function obrisiProjekat(Request $request, $id)
    {
        $projekat = Projekat::where('admin_id', $request->user()->id)->findOrFail($id);
        $projekat->delete();
        return response()->json(['poruka' => 'Projekat je uspešno obrisan.']);
    }

    public function getRadniciZaProjekat(Request $request, $id)
    {
        $admin      = $request->user();
        $sviRadnici = User::where('nadredjeni_id', $admin->id)->get();
        $projekat   = Projekat::where('admin_id', $admin->id)->findOrFail($id);

        // Niz ID-jeva radnika koji već rade na ovom projektu
        $radniciNaProjektu = $projekat->radnici()->pluck('users.id')->toArray();

        // Pravimo mapu svih radnika sa informacijom da li su čekirani
        $rezultat = $sviRadnici->map(function ($r) use ($radniciNaProjektu) {
            return [
                'id'               => $r->id,
                'name'             => $r->name,
                'role'             => $r->role, // <-- OVO NAM JE FALILO ZA BOJU TAGOVA
                'radi_na_projektu' => in_array($r->id, $radniciNaProjektu),
            ];
        });

        return response()->json($rezultat);
    }

    public function sacuvajRadnikeProjekta(Request $request, $id)
    {
        $projekat = Projekat::where('admin_id', $request->user()->id)->findOrFail($id);
        // Laravel sync() automatski briše one kojih nema u nizu i dodaje nove
        $projekat->radnici()->sync($request->radnici ?? []);
        return response()->json(['poruka' => 'Radnici su uspešno ažurirani.']);
    }

    public function promeniUlogu(Request $request, $id)
    {
        $radnik = User::where('nadredjeni_id', $request->user()->id)->findOrFail($id);
        $radnik->update(['role' => $request->uloga]);
        return response()->json(['poruka' => 'Uloga promenjena']);
    }
}
