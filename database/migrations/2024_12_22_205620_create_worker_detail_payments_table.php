<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('worker_detail_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('worker_group_id');
            $table->foreign('worker_group_id')
                ->references('id')
                ->on('worker_groups')
                ->onDelete('cascade');
            $table->unsignedBigInteger('worker_payment_id');
            $table->foreign('worker_payment_id')
                ->references('id')
                ->on('worker_payments')
                ->onDelete('cascade');
            $table->unsignedBigInteger("upah")->default(0);
            $table->unsignedBigInteger("pinjaman")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_detail_payments');
    }
};
