<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TenantAdminController extends Controller
{
    public function showLoginForm()
    {
        $tenant = app()->bound('currentTenant') ? app('currentTenant') : null;

        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('tenant.admin.dashboard', ['subdomain' => optional($tenant)->subdomain]);
        }

        return view('tenant.admin.login', compact('tenant'));
    }

    public function login(Request $request)
    {
        $tenant = app()->bound('currentTenant') ? app('currentTenant') : null;

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($tenant && strtolower($credentials['email']) !== strtolower($tenant->email)) {
            return back()->withErrors([
                'email' => 'This account does not match the active tenant subdomain.',
            ])->onlyInput('email');
        }

        if (Auth::attempt(array_merge($credentials, ['role' => 'admin', 'is_active' => true]), $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('tenant.admin.dashboard', ['subdomain' => optional($tenant)->subdomain]));
        }

        return back()->withErrors([
            'email' => 'Invalid credentials for this tenant admin portal.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $tenant = app()->bound('currentTenant') ? app('currentTenant') : null;
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('tenant.admin.login', ['subdomain' => optional($tenant)->subdomain]);
    }

    public function dashboard()
    {
        $tenant = app('currentTenant');
        // Global scope in Blog model automatically limits results to this tenant's blogs
        $blogs = Blog::latest()->get();

        return view('tenant.admin.dashboard', compact('tenant', 'blogs'));
    }

    public function createBlog()
    {
        $tenant = app('currentTenant');
        return view('tenant.admin.create_blog', compact('tenant'));
    }

    public function storeBlog(Request $request)
    {
        $tenant = app('currentTenant');

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);

        $slug = Str::slug($request->title) . '-' . Str::random(5);

        Blog::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'status' => $request->status,
            'user_id' => $tenant->id,
        ]);

        return redirect()->route('tenant.admin.dashboard', ['subdomain' => $tenant->subdomain])
            ->with('success', 'Blog post created successfully!');
    }
}
