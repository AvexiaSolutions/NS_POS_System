<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('sale_id');
            $table->string('invoice_no');
            $table->unsignedBigInteger('user_id');
            $table->decimal('total_refund', 10, 2)->default(0);
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
        });

        Schema::create('return_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('return_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('qty', 10, 2);
            $table->decimal('refund_amount', 10, 2);
            $table->string('type')->default('return');
            $table->timestamps();

            $table->foreign('return_id')->references('id')->on('returns')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('return_items');
        Schema::dropIfExists('returns');
    }
};
