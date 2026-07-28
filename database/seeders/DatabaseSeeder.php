<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create SuperAdmin Account
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'subdomain' => null,
                'is_active' => true,
            ]
        );

        // 2. Create Demo Tenant Admin (tech)
        $techAdmin = User::firstOrCreate(
            ['email' => 'tech@example.com'],
            [
                'name' => 'Tech Blog Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'subdomain' => 'tech',
                'is_active' => true,
            ]
        );

        // 3. Create Sample Blogs for Tech Tenant
        Blog::firstOrCreate(
            ['slug' => 'welcome-to-tech-subdomain-blog'],
            [
                'user_id' => $techAdmin->id,
                'title' => 'Welcome to Tech Subdomain Blog!',
                'content' => 'This is an isolated blog post automatically scoped to the tech subdomain on Laravel 13 dynamic multi-tenancy.',
                'status' => 'published',
            ]
        );

        Blog::firstOrCreate(
            ['slug' => 'laravel-13-multi-tenant-architecture'],
            [
                'user_id' => $techAdmin->id,
                'title' => 'Laravel 13 Dynamic Subdomain Architecture Guide',
                'content' => 'Building dynamic subdomains with Eloquent global scope isolation allows scalable SaaS blogging platforms on shared hosting.',
                'status' => 'published',
            ]
        );
    }
}
