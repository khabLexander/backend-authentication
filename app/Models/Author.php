<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;
    protected $table = 'app.authors';

    protected $fillable = [
        'age',
        'email',
        'identification',
        'names',
        'phone',
    ];

    // uno a uno
    /*
    function project(){
        return $this->belongsTo(Project::class);
    }
    */

    // uno a varios
    function project(){
        return $this->belongsTo(Project::class);
    }


    // varios a varios
    /*
    function projects(){
        return $this->belongsToMany(Project::class)->withTimestamps();
    }
    */

}
