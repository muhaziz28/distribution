<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkerPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "project_id",
        "transaction_date",
        "total",
        "attachment",
        "week",
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
