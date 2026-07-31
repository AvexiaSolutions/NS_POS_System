<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('suppliers', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->uuid('branch_id')->nullable();
        $table->string('company_name');
        $table->string('contact_person')->nullable();
        $table->string('phone');
        $table->string('email')->nullable();
        $table->text('address')->nullable();
        $table->decimal('credit_balance', 15, 2)->default(0);
        $table->timestamps();
    });
}


    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
