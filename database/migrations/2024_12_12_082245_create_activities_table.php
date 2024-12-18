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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('block_id');
            $table->integer('is_block_activity')->nullable();
            $table->string('activity_name')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Join Tabel Worker/Tukang
            $table->foreign('block_id')
                ->references('id')
                ->on('blocks')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
