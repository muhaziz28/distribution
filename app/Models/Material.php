<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "bahan_id",
        "qty",
        "vendor_id",
    ];

    public function bahan()
    {
        return $this->belongsTo(Bahan::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
