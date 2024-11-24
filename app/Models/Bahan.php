<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bahan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "nama_bahan",
        "qty",
        "satuan_id"
    ];

    public function satuan()
    {
        return $this->belongsTo(Satuan::class)->withTrashed();
    }

    public function scopeForDataTable($query)
    {
        return $query->with(['satuan' => function ($q) {
            $q->withTrashed();
        }]);
    }

    public function scopeFindIncludingTrashed($query, $bahan)
    {
        return $query->withTrashed()->where('nama_bahan', $bahan);
    }

    public function scopeCheckDuplicate($query, $bahan, $excludeId = null)
    {
        return $query->where('nama_bahan', $bahan)
            ->when($excludeId, function ($q) use ($excludeId) {
                $q->where('id', '!=', $excludeId);
            });
    }
}
