<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'photo',
        'telephone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    public function eleves()
    {
        return $this->hasMany(Eleve::class, 'paren_id');
    }

    public function paren()
    {
        return $this->hasOne(Paren::class, 'user_id');
    }

    public function parens()
    {
        return $this->hasMany(Paren::class, 'user_id');
    }

    public function getSidebarViewAttribute()
    {
        return match ($this->role) {
            'admin'       => 'components.sidebar.sidebar-admin',
            'censeur'     => 'components.sidebar.sidebar-censeur',
            'parent'      => 'components.sidebar.sidebar-parent',
            'secretaire'  => 'components.sidebar.sidebar-secretaire',
            'directeur'   => 'components.sidebar.sidebar-directeur',
            'comptable'   => 'components.sidebar.sidebar-comptable',
            'surveillant' => 'components.sidebar.sidebar-surveillant',
            'enseignant'  => 'components.sidebar.sidebar-enseignant',
            default       => 'components.sidebar.sidebar-default',
        };
    }
}