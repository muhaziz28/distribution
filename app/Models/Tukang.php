<?php

namespace App\Models;

use App\Http\Controllers\BlockMaterialDistributionController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

class Tukang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tukang';

    protected $fillable = [
        'nama_tukang',
        'no_hp',
        'is_active',
    ];


    public function blockTukang()
    {
        return $this->belongsToMany(BlockMaterialDistribution::class);
    }

    public function workerAssignments()
    {
        return $this->hasMany(WorkerAssigments::class);
    }
}
