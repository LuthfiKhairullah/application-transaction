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
        Schema::create('t_sales_det', function (Blueprint $table) {
            $table->id();
            $table->integer('sales_id');
            $table->integer('barang_id');
            $table->float('harga_bandrol');
            $table->integer('qty');
            $table->float('diskon_pct')->nullable();
            $table->float('diskon_nilai')->nullable();
            $table->float('harga_diskon');
            $table->float('total');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_sales_det');
    }
};
