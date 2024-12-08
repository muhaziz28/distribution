<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "no_hp",
    ];

    public function scopeSearch($query, $search)
    {
        if (!empty($search) && is_string($search)) {
            return $query->where('name', 'like', '%' . $search . '%');
        }

        return $query;
    }
}
