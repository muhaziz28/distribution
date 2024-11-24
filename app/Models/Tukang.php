<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

class Tukang extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_tukang',
        'no_hp',
        'is_active',
    ];


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
