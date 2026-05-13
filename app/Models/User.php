<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'nadredjeni_id', // Dodato
        'max_radnika',   // Dodato
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // --- RELACIJE ---

    // 1. Daje nam Admina koji je iznad ovog radnika/logistike
    public function nadredjeni()
    {
        return $this->belongsTo(User::class, 'nadredjeni_id');
    }

    // 2. Daje nam sve radnike koji su ispod ovog Admina
    public function mojiRadnici()
    {
        return $this->hasMany(User::class, 'nadredjeni_id');
    }

    // 3. Koji projekti su mu dodeljeni
    public function projekti()
    {
        return $this->belongsToMany(Projekat::class, 'projekat_radnik');
    }
}
