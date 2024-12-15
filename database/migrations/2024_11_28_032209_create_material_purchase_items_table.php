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
            Schema::create('material_purchase_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('material_purchases_id');
                $table->foreign('material_purchases_id')
                    ->references('id')
                    ->on('material_purchases')
                    ->onDelete('cascade');
                $table->unsignedBigInteger('bahan_id');
                $table->foreign('bahan_id')
                    ->references('id')
                    ->on('bahans')
                    ->onDelete('cascade');
                $table->integer("qty");
                $table->bigInteger("harga_satuan");
                $table->bigInteger("total");
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('material_purchase_items');
        }
    };
