-- ডাটাবেজ তৈরি এবং সিলেক্ট করা
CREATE DATABASE IF NOT EXISTS `saas_pos` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `saas_pos`;

-- =======================================================================
-- 📂 ১. লুকআপ টেবিলসমূহ (Lookup Tables)
-- =======================================================================

-- টেন্যান্ট স্ট্যাটাস টেবিল
CREATE TABLE `tenant_statuses` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `name` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- প্যাকেজ টেবিল
CREATE TABLE `packages` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `name` VARCHAR(255) NOT NULL,
    `price` DECIMAL(8, 2) NOT NULL DEFAULT '0.00',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- পেমেন্ট মেথড টেবিল
CREATE TABLE `payment_methods` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `name` VARCHAR(255) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT '1',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =======================================================================
-- 🏢 ২. কোর টেন্যান্ট ও তার অধীনস্থ টেবিলসমূহ (Tenant Core)
-- =======================================================================

-- মূল টেন্যান্ট বা কোম্পানি টেবিল
CREATE TABLE `tenants` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `business_name` VARCHAR(255) NOT NULL,
    `owner_name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `phone` VARCHAR(255) NOT NULL UNIQUE,
    `tenant_status_id` BIGINT UNSIGNED NOT NULL DEFAULT '1', -- লজিক্যাল আইডি (কোনো FK লক নেই)
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- সাবস্ক্রিপশন টেবিল
CREATE TABLE `subscriptions` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `tenant_id` BIGINT UNSIGNED NOT NULL,
    `package_id` BIGINT UNSIGNED NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `status` VARCHAR(255) NOT NULL DEFAULT 'trial',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- আউটলেট বা ব্রাঞ্চ টেবিল
CREATE TABLE `outlets` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `tenant_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `location` TEXT DEFAULT NULL,
    `phone` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =======================================================================
-- 👥 ৩. ইউজার টেবিল (SaaS Multi-Tenant Structure)
-- =======================================================================

CREATE TABLE `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `tenant_id` BIGINT UNSIGNED NOT NULL,
    `outlet_id` BIGINT UNSIGNED DEFAULT NULL,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) DEFAULT NULL,
    `phone` VARCHAR(255) NOT NULL UNIQUE,
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(255) NOT NULL DEFAULT 'cashier',
    `status` TINYINT(1) NOT NULL DEFAULT '1',
    `remember_token` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =======================================================================
-- 📦 ৪. ইনভেন্টরি মডিউল (Inventory Part)
-- =======================================================================

-- ক্যাটাগরি টেবিল
CREATE TABLE `categories` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `tenant_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- প্রোডাক্ট টেবিল (সার্চ স্পিড ধরে রাখতে ইনডেক্স ব্যবহার করা হয়েছে)
CREATE TABLE `products` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `tenant_id` BIGINT UNSIGNED NOT NULL,
    `category_id` BIGINT UNSIGNED DEFAULT NULL,
    `name` VARCHAR(255) NOT NULL,
    `sku` VARCHAR(255) DEFAULT NULL,
    `cost_price` DECIMAL(10, 2) NOT NULL DEFAULT '0.00',
    `sale_price` DECIMAL(10, 2) NOT NULL DEFAULT '0.00',
    `status` TINYINT(1) NOT NULL DEFAULT '1',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    INDEX `products_tenant_id_sku_index` (`tenant_id`, `sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =======================================================================
-- 💰 ৫. সেলস মডিউল (Sales & Invoicing Part)
-- =======================================================================

-- মেইন অর্ডার টেবিল
CREATE TABLE `orders` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `invoice_no` VARCHAR(255) NOT NULL UNIQUE,
    `tenant_id` BIGINT UNSIGNED NOT NULL,
    `outlet_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `total_amount` DECIMAL(10, 2) NOT NULL,
    `discount` DECIMAL(10, 2) NOT NULL DEFAULT '0.00',
    `net_amount` DECIMAL(10, 2) NOT NULL,
    `payment_method_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    INDEX `orders_tenant_id_outlet_id_created_at_index` (`tenant_id`, `outlet_id`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- অর্ডার আইটেমস টেবিল
CREATE TABLE `order_items` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` BIGINT UNSIGNED NOT NULL,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `quantity` INT NOT NULL,
    `unit_price` DECIMAL(10, 2) NOT NULL,
    `subtotal` DECIMAL(10, 2) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =======================================================================
-- 🔐 ৬. ল্যারাভেলের ইন্টারনাল সিস্টেম টেবিলসমূহ (Framework Essentials)
-- =======================================================================

CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(255) NOT NULL PRIMARY KEY,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL PRIMARY KEY,
    `user_id` BIGINT UNSIGNED DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    INDEX `sessions_user_id_index` (`user_id`),
    INDEX `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL PRIMARY KEY,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL PRIMARY KEY,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED DEFAULT NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    INDEX `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;