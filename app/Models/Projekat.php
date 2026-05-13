<?php
namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Projekat extends Model
{
    protected $table = 'projekti'; // DODAJ OVO — kaže Laravelu tačno ime tabele

    protected $fillable = ['naziv', 'opis', 'klijent', 'status', 'admin_id'];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function radnici()
    {
        return $this->belongsToMany(User::class, 'projekat_radnik');
    }
}
