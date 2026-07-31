<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('cash_books', function (Blueprint $table) {
        $table->id();
        $table->string('description');
        $table->enum('type', ['in', 'out']);
        $table->string('category');
        $table->decimal('amount', 10, 2);
        $table->foreignId('bank_account_id')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('cash_books');
    }
};
