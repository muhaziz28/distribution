<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkerAttendances extends Model
{
    use HasFactory;

    protected $fillable = [
        "worker_id",
        "activity_id",
        "durasi_kerja",
        "upah",
        "pinjaman",
    ];


    public function tukang()
    {
        return $this->belongsTo(Tukang::class, 'worker_id')->withTrashed();
    }

    public function activity()
    {
        return $this->belongsTo(Activities::class, 'activity_id');
    }
}
