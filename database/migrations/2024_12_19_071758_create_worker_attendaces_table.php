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
        Schema::create('worker_attendaces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('worker_group_id');
            $table->foreign('worker_group_id')
                ->references('id')
                ->on('worker_groups')
                ->onDelete('cascade');
            $table->integer("durasi_kerja");
            $table->date("tanggal");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_attendaces');
    }
};
