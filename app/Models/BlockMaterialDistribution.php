<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlockMaterialDistribution extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "block_id",
        "material_id",
        "distributed_qty",
        "distribution_date",
        "returned_qty",
        "returned_date",
    ];

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function tukang()
    {
        return $this->belongsTo(Tukang::class);
    }
}
