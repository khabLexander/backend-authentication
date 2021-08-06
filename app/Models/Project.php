<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $table = 'app.projects';

    protected $fillable = [
        'code',
        'date',
        'description',
        'approved',
        'title',
    ];

    // protected $attributes =['full_name'];

    protected $casts = [
        'is_approved' => 'boolean',
        'date' => 'datetime:Y-m-d',
    ];

    // uno a uno
    /*
    function author(){
        return $this->hasOne(Author::class);
    }
    */

    // uno a varios
    function authors()
    {
        return $this->hasMany(Author::class);
    }

    // varios a varios
    /*
    function authors(){
        return $this->belongsToMany(Author::class)->withTimestamps();;
    }
    */

    // Mutators
    function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }

    // Accessors
//    function getFullNameAttribute()
//    {
//        return $this->attributes['code'].$this->attributes['description'];
//    }
}
