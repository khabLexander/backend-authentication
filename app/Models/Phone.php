<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    protected $table = 'authentication.phones';

    protected $fillable = [
        'number',
        'main',
    ];

    public function phoneable()
    {
        return $this->morphTo();
    }
}
