<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('project', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_anggaran');
            $table->string('kegiatan');
            $table->string('pekerjaan');
            $table->string('lokasi');
            $table->enum('status', ['pending', 'process', 'finished'])->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project');
    }
};
