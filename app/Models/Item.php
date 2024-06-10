<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
<<<<<<< HEAD
    protected $guarded = [];

    // public $timestamps = false;
=======
    protected $fillable = ['kode_pajak', 'nama_pajak','deskripsi','tarif_pajak','tanggal_berlaku'];
>>>>>>> 9939541fdbddec7dea304a149db9b835e026c4d9

    public function user() 
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function scopeActive( $query) 
    {
        return $query->where('status', 1);
    }
}
