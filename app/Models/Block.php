<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "project_id",
        "block",
        "type",
        "harga",
        "luas_tanah",
        "luas_bangunan",
        "customer_id",
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
