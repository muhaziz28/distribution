<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkerPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "block_id",
        "week",
        "payment_date",
        "attachment"
    ];

    public function workerDetailPayments()
    {
        return $this->hasMany(WorkerDetailPayment::class, 'worker_payment_id');
    }
}
