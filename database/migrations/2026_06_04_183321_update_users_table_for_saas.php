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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique(); 
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // এখানে status কলামটি যোগ করা হয়েছে
            $table->boolean('status')->default(true); 
            
            $table->rememberToken();
            
            // রোল এবং টেন্যান্ট রিলেশনশিপ
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('cascade');
            $table->foreignId('tenant_id')->nullable();
            $table->foreignId('outlet_id')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};