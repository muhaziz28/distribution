<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "block_id",
        "payment_type",
        "payment_date",
        "total",
        "note",
        "attachment"
    ];

    public function block()
    {
        return $this->belongsTo(Block::class);
    }
}
