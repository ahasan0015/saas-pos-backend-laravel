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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('outlet_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('net_amount', 10, 2);
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->timestamps();

            $table->index(['tenant_id', 'outlet_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
