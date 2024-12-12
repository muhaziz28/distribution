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
        Schema::create('worker_assigments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('block_id');
            $table->unsignedBigInteger('worker_id');
            $table->date('join_date');
            $table->softDeletes();

            // Join Tabel Block
            $table->foreign('block_id')
                ->references('id')
                ->on('blocks')
                ->onDelete('cascade');

            // Join Tabel Worker/Tukang
            $table->foreign('worker_id')
                ->references('id')
                ->on('tukang')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_assigments');
    }
};
