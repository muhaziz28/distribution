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
        Schema::create('worker_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tukang_id');
            $table->foreign('tukang_id')
                ->references('id')
                ->on('tukang')
                ->onDelete('cascade');
            $table->unsignedBigInteger('activity_id');
            $table->foreign('activity_id')
                ->references('id')
                ->on('activities')
                ->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_groups');
    }
};
