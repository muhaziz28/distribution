<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkerProject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'worker_id',
        'project_id',
        'join_date'
    ];

    public function tukang()
    {
        return $this->belongsTo(Tukang::class, 'worker_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
