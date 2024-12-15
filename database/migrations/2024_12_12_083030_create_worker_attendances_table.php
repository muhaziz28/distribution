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
        Schema::create('worker_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('worker_id');
            $table->unsignedBigInteger('activity_id');
            $table->double('durasi_kerja');
            $table->integer('upah');
            $table->integer('pinjaman')->nullable();

            // Join Tabel Worker/Tukang
            $table->foreign('worker_id')
                ->references('id')
                ->on('tukang')
                ->onDelete('cascade');

            // Join Tabel Activity
            $table->foreign('activity_id')
                ->references('id')
                ->on('activities')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_attendances');
    }
};
