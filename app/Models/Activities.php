<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activities extends Model
{
    use HasFactory;

    protected $fillable = [
        "block_id",
        "is_block_activity",
        "activity_name",
        "date",
        "total",

    ];

    // Relasi ke block 
    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function workerAttendances()
    {
        return $this->hasMany(WorkerAttendances::class);
    }
}
