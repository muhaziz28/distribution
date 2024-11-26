<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Satuan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "satuan"
    ];

    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            return $query->where('satuan', 'like', '%' . $search . '%');
        }

        return $query;
    }

    public function scopeFindIncludingTrashed($query, $satuan)
    {
        return $query->withTrashed()->where('satuan', $satuan);
    }

    public function scopeCheckDuplicate($query, $satuan, $excludeId = null)
    {
        return $query->where('satuan', $satuan)
            ->when($excludeId, function ($q) use ($excludeId) {
                $q->where('id', '!=', $excludeId);
            });
    }
}
