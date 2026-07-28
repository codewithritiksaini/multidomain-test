@extends('layouts.app')

@section('title', 'Tenant Admin Login')
@section('brand', optional($tenant)->name ? $tenant->name . ' Admin' : 'Tenant Admin')

@section('nav_links')
    @if($tenant)
        <a href="http://{{ $tenant->subdomain }}.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }}:8000" class="text-xs text-slate-400 hover:text-white transition">
            &larr; Back to Blog Frontend
        </a>
    @endif
@endsection

@section('content')
<div class="max-w-md mx-auto py-12">
    <div class="bg-slate-950 border border-slate-800 rounded-2xl p-8 space-y-6 shadow-2xl">
        <div class="text-center space-y-2">
            <span class="px-2.5 py-1 text-xs font-mono font-bold text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-md">
                {{ optional($tenant)->subdomain }}.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }}
            </span>
            <h2 class="text-2xl font-extrabold text-white">Tenant Admin Login</h2>
            <p class="text-xs text-slate-400">Manage blog posts for {{ optional($tenant)->name ?? 'this tenant' }}</p>
        </div>

        <form method="POST" action="{{ route('tenant.admin.login', ['subdomain' => optional($tenant)->subdomain]) }}" class="space-y-4">
            @csrf

            <div class="space-y-1">
                <label class="block text-xs font-semibold text-slate-300">Tenant Admin Email</label>
                <input type="email" name="email" value="{{ old('email', optional($tenant)->email ?? 'tech@example.com') }}" required autofocus
                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500 transition">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-semibold text-slate-300">Password</label>
                <input type="password" name="password" value="password" required
                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500 transition">
            </div>

            <button type="submit" class="w-full py-3 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition shadow-lg shadow-indigo-600/20">
                Sign In to Tenant Admin
            </button>
        </form>
    </div>
</div>
@endsection
