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

        $response = $this->post('http://localhost/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('http://localhost/dashboard');
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
            ->post('http://localhost/admins', [
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

    public function test_superadmin_can_edit_and_update_tenant_admin()
    {
        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        $tenant = User::create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'subdomain' => 'oldsubdomain',
            'is_active' => true,
        ]);

        $response = $this->actingAs($superadmin)
            ->put("http://localhost/admins/{$tenant->id}", [
                'name' => 'Updated Name',
                'email' => 'new@example.com',
                'subdomain' => 'newsubdomain',
                'is_active' => 1,
            ]);

        $response->assertRedirect(route('superadmin.dashboard'));
        $this->assertDatabaseHas('users', [
            'id' => $tenant->id,
            'name' => 'Updated Name',
            'email' => 'new@example.com',
            'subdomain' => 'newsubdomain',
        ]);
    }

    public function test_superadmin_can_toggle_tenant_admin_status()
    {
        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        $tenant = User::create([
            'name' => 'Tech Admin',
            'email' => 'tech@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'subdomain' => 'tech',
            'is_active' => true,
        ]);

        $response = $this->actingAs($superadmin)
            ->patch("http://localhost/admins/{$tenant->id}/toggle-status");

        $response->assertRedirect(route('superadmin.dashboard'));
        $this->assertDatabaseHas('users', [
            'id' => $tenant->id,
            'is_active' => false,
        ]);
    }

    public function test_superadmin_can_delete_tenant_admin()
    {
        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        $tenant = User::create([
            'name' => 'Delete Me',
            'email' => 'deleteme@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'subdomain' => 'deleteme',
            'is_active' => true,
        ]);

        $response = $this->actingAs($superadmin)
            ->delete("http://localhost/admins/{$tenant->id}");

        $response->assertRedirect(route('superadmin.dashboard'));
        $this->assertDatabaseMissing('users', [
            'id' => $tenant->id,
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
