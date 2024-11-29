<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialPurchaseItems extends Model
{
    use HasFactory;

    protected $fillable = [
        "material_purchases_id",
        "bahan_id",
        "qty",
        "harga_satuan",
    ];
}
