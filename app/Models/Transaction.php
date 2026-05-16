<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'category_id', 'type', 'amount', 'date', 'description'];

    // Relasi: Transaksi ini milik siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Transaksi ini masuk kategori apa?
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}