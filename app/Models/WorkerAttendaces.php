<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerAttendaces extends Model
{
    use HasFactory;

    protected $fillable = [
        "worker_group_id",
        "durasi_kerja",
        "tanggal"
    ];


    public function workerGroup()
    {
        return $this->belongsTo(WorkerGroup::class, 'worker_group_id', 'id');
    }
}
