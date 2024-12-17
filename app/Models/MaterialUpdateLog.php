<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialUpdateLog extends Model
{
    use HasFactory;

    protected $fillable = [
        "material_id",
        "previous_qty",
        "new_qty",
        "updated_by",
        "note",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "udpated_by");
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
