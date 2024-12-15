<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentAdditionalItems extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "block_id",
        "payment_id",
        "item_name",
        "item_description",
        "total",
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
