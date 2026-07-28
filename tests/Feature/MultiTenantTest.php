<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MultiTenantTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_be_created_and_login()
    {
        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        $response = $this->post('http://admin.localhost/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('http://admin.localhost/dashboard');
        $this->assertAuthenticatedAs($superadmin);
    }

    public function test_superadmin_can_create_tenant_admin_with_subdomain()
    {
        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        $response = $this->actingAs($superadmin)
            ->post('http://admin.localhost/admins', [
                'name' => 'Travel Admin',
                'email' => 'travel@example.com',
                'password' => 'password',
                'subdomain' => 'travel',
            ]);

        $response->assertRedirect(route('superadmin.dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'travel@example.com',
            'subdomain' => 'travel',
            'role' => 'admin',
        ]);
    }

    public function test_tenant_subdomain_displays_only_its_own_blogs()
    {
        $techAdmin = User::create([
            'name' => 'Tech Admin',
            'email' => 'tech@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'subdomain' => 'tech',
        ]);

        $travelAdmin = User::create([
            'name' => 'Travel Admin',
            'email' => 'travel@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'subdomain' => 'travel',
        ]);

        $techBlog = Blog::create([
            'user_id' => $techAdmin->id,
            'title' => 'Tech Post 101',
            'slug' => 'tech-post-101',
            'content' => 'Content for Tech',
            'status' => 'published',
        ]);

        $travelBlog = Blog::create([
            'user_id' => $travelAdmin->id,
            'title' => 'Travel to Paris',
            'slug' => 'travel-to-paris',
            'content' => 'Content for Travel',
            'status' => 'published',
        ]);

        // Request Tech Subdomain
        $responseTech = $this->get('http://tech.localhost/');

        $responseTech->assertStatus(200);
        $responseTech->assertSee('Tech Post 101');
        $responseTech->assertDontSee('Travel to Paris');

        // Request Travel Subdomain
        $responseTravel = $this->get('http://travel.localhost/');

        $responseTravel->assertStatus(200);
        $responseTravel->assertSee('Travel to Paris');
        $responseTravel->assertDontSee('Tech Post 101');
    }

    public function test_invalid_subdomain_returns_404()
    {
        $response = $this->get('http://invalidsubdomain.localhost/');

        $response->assertStatus(404);
    }
}
