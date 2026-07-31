<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
{
    Schema::create('product_rolls', function (Blueprint $table) {
        $table->id(); 
        
        $table->foreignUuid('product_id')->constrained()->onDelete('cascade'); 

        $table->string('roll_length');
        $table->decimal('roll_price', 10, 2);
        $table->timestamps();
    });

    Schema::table('products', function (Blueprint $table) {
        $table->decimal('discount_price', 10, 2)->nullable()->default(0);
        $table->string('discount_type')->default('amount');
    });

    Schema::table('users', function (Blueprint $table) {
        $table->boolean('is_active')->default(true);
    });
}


    public function down(): void
    {
      
    }
};
