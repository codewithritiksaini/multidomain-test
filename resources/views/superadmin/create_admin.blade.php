@extends('layouts.app')

@section('title', 'Create Tenant Admin')
@section('brand', 'SuperAdmin Panel')

@section('nav_links')
    <a href="{{ route('superadmin.dashboard') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
        &larr; Back to Dashboard
    </a>
@endsection

@section('content')
<div class="max-w-xl mx-auto py-4">
    <div class="bg-white border border-slate-200/80 rounded-2xl p-8 space-y-6 shadow-xl shadow-slate-200/50">
        <div class="border-b border-slate-100 pb-4">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Create New Tenant Subdomain</h2>
            <p class="text-xs text-slate-500 mt-1">Register a new Tenant Admin and assign an isolated subdomain</p>
        </div>

        <form method="POST" action="{{ route('superadmin.admins.store') }}" class="space-y-5">
            @csrf

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Admin Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Travel Admin" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition duration-150">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Admin Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g. travel@example.com" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition duration-150">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Password</label>
                <input type="password" name="password" required placeholder="Minimum 6 characters"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition duration-150">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Subdomain Identifier</label>
                <div class="flex items-center gap-2">
                    <input type="text" name="subdomain" value="{{ old('subdomain') }}" placeholder="e.g. travel" required
                        class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm font-mono focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition duration-150">
                    <span class="text-xs text-slate-500 font-mono font-bold">.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }}</span>
                </div>
                <p class="text-[11px] text-slate-400">Only lowercase letters, numbers, and dashes (e.g., <code>travel</code>, <code>tech-hub</code>).</p>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('superadmin.dashboard') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition duration-150 shadow-md shadow-indigo-600/20">
                    Create Tenant Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
