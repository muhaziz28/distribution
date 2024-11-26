<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialPurchases extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "project_id",
        "vendor_id",
        "attachment",
    ];

    public function project()
    {
        return $this->belongsTo(Project::class)->withTrashed();
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class)->withTrashed();
    }
}
