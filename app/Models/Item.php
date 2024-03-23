<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['kode_pajak', 'nama_pajak','deskripsi','tarif_pajak','tanggal_berlaku'];

    public function user() 
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function scopeActive( $query) 
    {
        return $query->where('status', 1);
    }
}
