<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Outlet;
use App\Models\Package;
use App\Models\Product;
use App\Models\Category;
use App\Models\TenantStatus;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // =======================================================================
        // ১. গ্লোবাল ডাটা সিডিং (সিস্টেমের মূল লুকআপ টেবিল)
        // =======================================================================
        
        TenantStatus::create(['slug' => 'active', 'name' => 'Active']);
        TenantStatus::create(['slug' => 'suspended', 'name' => 'Suspended']);

        Package::create(['slug' => 'basic', 'name' => 'Basic Plan', 'price' => 1000.00]);
        Package::create(['slug' => 'premium', 'name' => 'Premium Plan', 'price' => 3000.00]);

        PaymentMethod::create(['slug' => 'cash', 'name' => 'Cash', 'is_active' => true]);
        PaymentMethod::create(['slug' => 'bkash', 'name' => 'bKash', 'is_active' => true]);
        PaymentMethod::create(['slug' => 'card', 'name' => 'Card Payment', 'is_active' => true]);

        // =======================================================================
        // ২. কোম্পানি ১ (Tenant 1) - রক্সি গ্রুপ এবং তার ডাটা
        // =======================================================================
        
        $tenant1 = Tenant::factory()->create([
            'business_name' => 'Roxy Super Shop',
            'email' => 'roxy@owner.com'
        ]);

        // রক্সি শপের আউটলেটসমূহ
        $outlet1_1 = Outlet::factory()->create(['tenant_id' => $tenant1->id, 'name' => 'Dhaka Branch']);
        $outlet1_2 = Outlet::factory()->create(['tenant_id' => $tenant1->id, 'name' => 'Chittagong Branch']);

        // রক্সি শপের ইউজার ও ক্যাশিয়ার (পাসওয়ার্ড সবার '123456')
        User::factory()->create([
            'tenant_id' => $tenant1->id,
            'outlet_id' => null, // মেইন মালিক
            'name' => 'Roxy Admin',
            'phone' => '01711111111',
            'password' => Hash::make('123456'),
            'role' => 'admin'
        ]);

        User::factory()->create([
            'tenant_id' => $tenant1->id,
            'outlet_id' => $outlet1_1->id, // ঢাকা আউটলেট ক্যাশিয়ার
            'name' => 'Dhaka Cashier',
            'phone' => '01711111112',
            'password' => Hash::make('123456'),
            'role' => 'cashier'
        ]);

        // রক্সি শপের জন্য ক্যাটাগরি ও প্রোডাক্ট তৈরি
        $categories1 = Category::factory(3)->create(['tenant_id' => $tenant1->id]);
        foreach ($categories1 as $category) {
            Product::factory(5)->create([
                'tenant_id' => $tenant1->id,
                'category_id' => $category->id
            ]);
        }

        // =======================================================================
        // ৩. কোম্পানি ২ (Tenant 2) - অন্য একটি ডামি বিজনেস
        // =======================================================================
        
        $tenant2 = Tenant::factory()->create([
            'business_name' => 'Bengal Fashion',
            'email' => 'begal@owner.com'
        ]);

        $outlet2_1 = Outlet::factory()->create(['tenant_id' => $tenant2->id, 'name' => 'Sylhet Outlets']);

        User::factory()->create([
            'tenant_id' => $tenant2->id,
            'outlet_id' => null,
            'name' => 'Bengal Admin',
            'phone' => '01911111111',
            'password' => Hash::make('123456'),
            'role' => 'admin'
        ]);

        $categories2 = Category::factory(2)->create(['tenant_id' => $tenant2->id]);
        foreach ($categories2 as $category) {
            Product::factory(3)->create([
                'tenant_id' => $tenant2->id,
                'category_id' => $category->id
            ]);
        }
    }
}