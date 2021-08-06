<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class User extends Authenticatable implements Auditable, MustVerifyEmail
{
    use HasFactory;
    use Auditing;
    use Notifiable;
    use HasApiTokens;
    use SoftDeletes;

    const ATTEMPTS = 3;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }


    // Scopes
    public function scopeName($query, $value)
    {
        if ($value) {
            return $query->where('name', 'ILIKE', "%$value%");
        }
    }

    public function scopeLastname($query, $value)
    {
        if ($value) {
            return $query->where('lastname', 'ILIKE', "%$value%");
        }
    }

}
