@extends('layouts.app')

@section('title', 'Create Tenant Admin')
@section('brand', 'SuperAdmin Panel')

@section('nav_links')
    <a href="{{ route('superadmin.dashboard') }}" class="text-xs text-slate-400 hover:text-white transition">
        &larr; Back to Dashboard
    </a>
@endsection

@section('content')
<div class="max-w-xl mx-auto py-6">
    <div class="bg-slate-950 border border-slate-800 rounded-2xl p-8 space-y-6 shadow-2xl">
        <div class="border-b border-slate-800 pb-4">
            <h2 class="text-2xl font-extrabold text-white">Create New Tenant Subdomain</h2>
            <p class="text-xs text-slate-400">Register a new Tenant Admin and assign an isolated subdomain</p>
        </div>

        <form method="POST" action="{{ route('superadmin.admins.store') }}" class="space-y-5">
            @csrf

            <div class="space-y-1">
                <label class="block text-xs font-semibold text-slate-300">Admin Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Travel Admin" required
                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500 transition">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-semibold text-slate-300">Admin Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g. travel@example.com" required
                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500 transition">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-semibold text-slate-300">Password</label>
                <input type="password" name="password" required placeholder="Minimum 6 characters"
                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500 transition">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-semibold text-slate-300">Subdomain Identifier</label>
                <div class="flex items-center gap-2">
                    <input type="text" name="subdomain" value="{{ old('subdomain') }}" placeholder="e.g. travel" required
                        class="flex-1 px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm font-mono focus:outline-none focus:border-indigo-500 transition">
                    <span class="text-xs text-slate-400 font-mono">.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }}</span>
                </div>
                <p class="text-[11px] text-slate-500">Only letters, numbers, and dashes (e.g., <code>travel</code>, <code>tech-hub</code>).</p>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('superadmin.dashboard') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-400 hover:text-white transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition shadow-lg shadow-indigo-600/20">
                    Create Tenant Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
