<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerDetailPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        "worker_group_id",
        "worker_payment_id",
        "upah",
        "pinjaman"
    ];
}
