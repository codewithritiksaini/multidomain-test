<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        }
        return view('superadmin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(array_merge($credentials, ['role' => 'superadmin']), $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('superadmin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or non-superadmin account.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function index()
    {
        $admins = User::where('role', 'admin')->latest()->get();
        return view('superadmin.dashboard', compact('admins'));
    }

    public function createAdmin()
    {
        return view('superadmin.create_admin');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'subdomain' => 'required|alpha_dash|max:50|unique:users,subdomain',
        ]);

        $subdomain = Str::lower($request->subdomain);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'subdomain' => $subdomain,
            'is_active' => true,
        ]);

        $centralDomain = env('APP_CENTRAL_DOMAIN', 'localhost');
        $scheme = $request->getScheme();
        $port = $request->getPort();
        $portStr = ($port && !in_array($port, [80, 443])) ? ":{$port}" : "";
        $tenantUrl = "{$scheme}://{$subdomain}.{$centralDomain}{$portStr}";

        return redirect()->route('superadmin.dashboard')
            ->with('success', "Tenant Admin created successfully! Subdomain: [{$subdomain}]. Tenant URL: {$tenantUrl}");
    }

    public function editAdmin($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        return view('superadmin.edit_admin', compact('admin'));
    }

    public function updateAdmin(Request $request, $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'subdomain' => 'required|alpha_dash|max:50|unique:users,subdomain,' . $admin->id,
            'password' => 'nullable|min:6',
            'is_active' => 'required|boolean',
        ]);

        $subdomain = Str::lower($request->subdomain);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'subdomain' => $subdomain,
            'is_active' => (bool)$request->is_active,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('superadmin.dashboard')
            ->with('success', "Tenant Admin [{$admin->name}] updated successfully!");
    }

    public function toggleAdminStatus($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        $admin->update(['is_active' => !$admin->is_active]);

        $statusText = $admin->is_active ? 'activated' : 'deactivated';

        return redirect()->route('superadmin.dashboard')
            ->with('success', "Tenant Subdomain [{$admin->subdomain}] {$statusText} successfully!");
    }

    public function destroyAdmin($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        $subdomain = $admin->subdomain;
        $admin->delete();

        return redirect()->route('superadmin.dashboard')
            ->with('success', "Tenant Admin and Subdomain [{$subdomain}] deleted successfully!");
    }
}
