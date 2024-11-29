<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "nama_vendor",
        "alamat",
        "kontak",
    ];

    public function scopeSearch($query, $search)
    {
        if (!empty($search) && is_string($search)) {
            return $query->where('nama_vendor', 'like', '%' . $search . '%');
        }

        return $query;
    }

    public function scopeFindIncludingTrashed($query, $satuan)
    {
        return $query->withTrashed()->where('nama_vendor', $satuan);
    }

    public function scopeCheckDuplicate($query, $vendor, $excludeId = null)
    {
        return $query->where('nama_vendor', $vendor)
            ->when($excludeId, function ($q) use ($excludeId) {
                $q->where('id', '!=', $excludeId);
            });
    }
}
