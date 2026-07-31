<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('cashier');
            $table->rememberToken();
            $table->timestamps();
            
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
        });

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

        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id');
            $table->string('name');
            $table->string('code')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id');
            $table->uuid('category_id')->nullable();
            $table->uuid('supplier_id')->nullable();
            
            $table->string('barcode')->nullable();
            $table->string('product_name');
            
            $table->decimal('cost_price', 15, 2);
            $table->decimal('selling_price', 15, 2);
            $table->decimal('wholesale_price', 15, 2)->nullable();
            
            $table->decimal('qty', 15, 2)->default(0);
            $table->string('unit')->default('pcs'); 
            $table->decimal('alert_qty', 15, 2)->default(5);
            
            $table->boolean('has_warranty')->default(false);
            $table->integer('warranty_months')->default(0);
            
            $table->timestamps();
        });

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id');
            $table->string('bank_name');
            $table->string('account_name');
            $table->string('account_number');
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('cheques', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id');
            $table->enum('type', ['received', 'issued']);
            $table->string('cheque_number');
            $table->string('bank_name');
            $table->decimal('amount', 15, 2);
            $table->date('cheque_date');
            $table->date('realization_date');
            $table->enum('status', ['pending', 'cleared', 'bounced', 'returned'])->default('pending');
            $table->uuid('supplier_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id');
            $table->enum('type', ['income', 'expense', 'transfer']);
            $table->string('category'); 
            $table->decimal('amount', 15, 2);
            $table->string('payment_method');
            $table->uuid('bank_account_id')->nullable();
            $table->uuid('cheque_id')->nullable();
            $table->string('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->date('transaction_date');
            $table->timestamps();
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id');
            $table->uuid('user_id');
            $table->string('invoice_no')->unique();
            $table->decimal('sub_total', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->decimal('cash_received', 15, 2);
            $table->decimal('balance', 15, 2);
            $table->string('payment_method')->default('cash');
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->timestamps();
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sale_id');
            $table->uuid('product_id');
            $table->string('product_name');
            $table->decimal('qty', 15, 2);
            $table->decimal('price', 15, 2);
            $table->decimal('total', 15, 2);
            $table->boolean('has_warranty')->default(false);
            $table->date('warranty_expiry')->nullable();
            $table->timestamps();
            
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
        });

        Schema::create('returns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id');
            $table->uuid('sale_id');
            $table->uuid('product_id');
            $table->decimal('qty', 15, 2);
            $table->decimal('refund_amount', 15, 2);
            $table->string('reason')->nullable();
            $table->string('type')->default('return');
            $table->timestamps();
        });

        Schema::create('shop_settings', function (Blueprint $table) {
             $table->uuid('id')->primary();
             $table->uuid('branch_id')->nullable();
             $table->string('shop_name')->default('NS Enterprises');
             $table->string('shop_address')->nullable();
             $table->string('shop_phone')->nullable();
             $table->string('logo')->nullable();
             $table->timestamps();
        });

        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->string('sidebar_color')->default('#1e293b');
            $table->string('menu_text_color')->default('#94a3b8');
            $table->string('button_color')->default('#0d6efd');
            $table->string('icon_color')->default('#0d6efd');
            $table->string('text_color')->default('#1e293b');
            $table->string('theme_mode')->default('light');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_settings');
        Schema::dropIfExists('shop_settings');
        Schema::dropIfExists('returns');
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('cheques');
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('users');
        Schema::dropIfExists('branches');
    }
};
