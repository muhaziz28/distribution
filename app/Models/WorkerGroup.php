<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkerGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tukang_id',
        'activity_id'
    ];

    public function tukang()
    {
        return $this->belongsTo(Tukang::class, 'tukang_id', 'id');
    }

    public function workerAttendances()
    {
        return $this->hasMany(WorkerAttendaces::class, "worker_group_id");
    }
}
