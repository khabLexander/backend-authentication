<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $table = 'authentication.emails';

    protected $fillable = [
        'email',
        'main',
    ];

    public function emailable()
    {
        return $this->morphTo();
    }
}
