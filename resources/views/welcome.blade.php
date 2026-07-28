@extends('layouts.app')

@section('title', 'Laravel 13 Multi-Tenant Subdomain SaaS Portal')
@section('brand', 'Central SaaS Portal')

@section('nav_links')
    <a href="http://admin.{{ $centralDomain }}:8000/login" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 rounded-lg transition shadow-lg shadow-indigo-600/20">
        SuperAdmin Login &rarr;
    </a>
@endsection

@section('content')
<div class="space-y-12">
    <!-- Hero Banner -->
    <div class="text-center max-w-3xl mx-auto py-8 space-y-4">
        <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 rounded-full">
            Laravel 13 Dynamic Subdomain Platform
        </span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight">
            Multi-Tenant Subdomain Blog Engine
        </h1>
        <p class="text-slate-400 text-lg">
            High-performance single-database multi-tenancy with dynamic subdomain routing and Eloquent automatic data isolation.
        </p>
    </div>

    <!-- Active Tenant Subdomains Card Grid -->
    <div class="space-y-6">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <h2 class="text-xl font-bold text-white">Active Subdomain Blogs</h2>
            <span class="text-xs text-slate-400 font-mono">Total Tenants: {{ $tenants->count() }}</span>
        </div>

        @if($tenants->isEmpty())
            <div class="p-12 text-center border border-dashed border-slate-800 rounded-2xl bg-slate-900/50">
                <p class="text-slate-400 mb-4">No active tenant subdomains registered yet.</p>
                <a href="http://admin.{{ $centralDomain }}:8000/login" class="inline-block px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition">
                    Log in as SuperAdmin to create the first tenant
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($tenants as $tenant)
                    @php
                        $tenantUrl = "http://{$tenant->subdomain}.{$centralDomain}:8000";
                        $tenantAdminUrl = "http://{$tenant->subdomain}.{$centralDomain}:8000/admin/login";
                    @endphp
                    <div class="bg-slate-950 border border-slate-800 rounded-2xl p-6 hover:border-slate-700 transition flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-1 text-xs font-mono font-bold text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-md">
                                    {{ $tenant->subdomain }}.{{ $centralDomain }}
                                </span>
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            </div>
                            <h3 class="text-lg font-bold text-white">{{ $tenant->name }}</h3>
                            <p class="text-xs text-slate-400">Admin Email: {{ $tenant->email }}</p>
                        </div>

                        <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between gap-2">
                            <a href="{{ $tenantUrl }}" class="flex-1 text-center py-2 text-xs font-semibold text-white bg-slate-800 hover:bg-slate-700 rounded-lg transition">
                                Visit Blog Frontend &rarr;
                            </a>
                            <a href="{{ $tenantAdminUrl }}" class="px-3 py-2 text-xs font-semibold text-indigo-400 hover:text-indigo-300 bg-indigo-500/10 rounded-lg transition">
                                Admin Login
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
