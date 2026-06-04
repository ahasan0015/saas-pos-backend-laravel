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
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // রোল সিড করুন
        $this->call([
            RoleSeeder::class,
        ]);

        // গ্লোবাল ডাটা সিডিং
        TenantStatus::firstOrCreate(['slug' => 'active'], ['name' => 'Active']);
        TenantStatus::firstOrCreate(['slug' => 'suspended'], ['name' => 'Suspended']);

        Package::firstOrCreate(['slug' => 'basic'], ['name' => 'Basic Plan', 'price' => 1000.00]);
        Package::firstOrCreate(['slug' => 'premium'], ['name' => 'Premium Plan', 'price' => 3000.00]);

        PaymentMethod::firstOrCreate(['slug' => 'cash'], ['name' => 'Cash', 'is_active' => true]);
        PaymentMethod::firstOrCreate(['slug' => 'bkash'], ['name' => 'bKash', 'is_active' => true]);
        PaymentMethod::firstOrCreate(['slug' => 'card'], ['name' => 'Card Payment', 'is_active' => true]);

        // রোল আইডি সংগ্রহ করুন
        $adminRole = Role::firstOrCreate(['name' => 'admin'])->id;
        $cashierRole = Role::firstOrCreate(['name' => 'cashier'])->id;

        // কোম্পানি ১ (Tenant 1)
        $tenant1 = Tenant::factory()->create([
            'business_name' => 'Roxy Super Shop',
            'email' => 'roxy@owner.com'
        ]);

        $outlet1_1 = Outlet::factory()->create(['tenant_id' => $tenant1->id, 'name' => 'Dhaka Branch']);
        Outlet::factory()->create(['tenant_id' => $tenant1->id, 'name' => 'Chittagong Branch']);

        User::factory()->create([
            'tenant_id' => $tenant1->id,
            'name' => 'Roxy Admin',
            'phone' => '01711111111',
            'password' => Hash::make('123456'),
            'role_id' => $adminRole,
        ]);

        User::factory()->create([
            'tenant_id' => $tenant1->id,
            'outlet_id' => $outlet1_1->id,
            'name' => 'Dhaka Cashier',
            'phone' => '01711111112',
            'password' => Hash::make('123456'),
            'role_id' => $cashierRole
        ]);

        $categories1 = Category::factory(3)->create(['tenant_id' => $tenant1->id]);
        foreach ($categories1 as $category) {
            Product::factory(5)->create(['tenant_id' => $tenant1->id, 'category_id' => $category->id]);
        }

        // কোম্পানি ২ (Tenant 2)
        $tenant2 = Tenant::factory()->create([
            'business_name' => 'Bengal Fashion',
            'email' => 'begal@owner.com'
        ]);

        Outlet::factory()->create(['tenant_id' => $tenant2->id, 'name' => 'Sylhet Outlets']);

        User::factory()->create([
            'tenant_id' => $tenant2->id,
            'name' => 'Bengal Admin',
            'phone' => '01911111111',
            'password' => Hash::make('123456'),
            'role_id' => $adminRole, // এখানে 'role' পরিবর্তন করে 'role_id' দেওয়া হয়েছে
        ]);

        $categories2 = Category::factory(2)->create(['tenant_id' => $tenant2->id]);
        foreach ($categories2 as $category) {
            Product::factory(3)->create(['tenant_id' => $tenant2->id, 'category_id' => $category->id]);
        }
    }
}
