<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialPurchases extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "vendor_id",
        "transaction_date",
        "attachment",
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class)->withTrashed();
    }

    public function materialPurchaseItems()
    {
        return $this->hasMany(MaterialPurchaseItems::class);
    }

    public function getTotalAttribute()
    {
        return $this->materialPurchaseItems->sum('total');
    }
}
