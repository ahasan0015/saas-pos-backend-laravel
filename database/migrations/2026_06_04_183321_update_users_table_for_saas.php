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
        Schema::table('users', function (Blueprint $table) {
            // ডিফল্ট ইমেইল কলামকে নুলাবল করা এবং কাস্টম কলামগুলো সঠিক পজিশনে যোগ করা
            $table->string('email')->nullable()->change();
            $table->foreignId('tenant_id')->after('id')->constrained()->onDelete('cascade');
            $table->foreignId('outlet_id')->after('tenant_id')->nullable()->constrained()->onDelete('set null');
            $table->string('phone')->unique()->after('email');
            $table->string('role')->default('cashier')->after('password');
            $table->boolean('status')->default(true)->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
