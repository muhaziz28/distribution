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
        Schema::create('payment_additional_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("block_id");
            $table->foreign('block_id')
                ->references('id')
                ->on('blocks')
                ->onDelete('cascade');
            $table->unsignedBigInteger("payment_id");
            $table->foreign('payment_id')
                ->references('id')
                ->on('payments')
                ->onDelete('cascade');
            $table->string("item_name");
            $table->string("item_description")->nullable();
            $table->integer("total");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_additional_items');
    }
};
