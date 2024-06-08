<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $guarded = [];

    // public $timestamps = false;

    public function user() 
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function scopeActive( $query) 
    {
        return $query->where('status', 1);
    }
}
