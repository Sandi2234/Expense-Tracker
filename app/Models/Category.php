<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'slug', 'color'];

    // Relasi: Kategori dimiliki oleh User (bisa null untuk kategori default)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Satu kategori punya banyak transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}