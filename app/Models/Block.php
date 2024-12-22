<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "project_id",
        "block",
        "type",
        "harga",
        "luas_tanah",
        "luas_bangunan",
        "customer_id",
    ];

    public function scopeSearch($query, $search)
    {
        if (!empty($search) && is_string($search)) {
            return $query->where('block', 'like', '%' . $search . '%');
        }

        return $query;
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function workerAssignments()
    {
        return $this->hasMany(WorkerAssigments::class);
    }

    public function blockMaterialDistribution()
    {
        return $this->hasMany(BlockMaterialDistribution::class);
    }
}
