<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkerAssigments extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "block_id",
        "tukang_id",
        "join_date",
    ];

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function tukang()
    {
        return $this->belongsTo(Tukang::class);
    }
}
