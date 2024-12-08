<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'project';

    protected $fillable = [
        'tahun_anggaran',
        'kegiatan',
        'pekerjaan',
        'lokasi',
    ];


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function workerProjects()
    {
        return $this->hasMany(WorkerProject::class, 'project_id');
    }

    public function tukangs()
    {
        return $this->hasMany(Tukang::class, 'project_id');
    }
}
