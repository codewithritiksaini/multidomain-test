@extends('layouts.app')

@section('title', 'SuperAdmin Dashboard')
@section('brand', 'SuperAdmin Panel')

@section('nav_links')
    <span class="text-xs text-slate-400 font-medium">Logined: {{ auth()->user()->email }}</span>
    <form method="POST" action="{{ route('superadmin.logout') }}" class="inline">
        @csrf
        <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 rounded-lg transition">
            Logout
        </button>
    </form>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-800 pb-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white">Tenant Subdomains Directory</h1>
            <p class="text-xs text-slate-400">Manage multi-tenant admins and dynamic subdomain bindings</p>
        </div>
        <a href="{{ route('superadmin.admins.create') }}" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition shadow-lg shadow-indigo-600/20">
            + Create New Tenant Admin
        </a>
    </div>

    <!-- Table of Tenant Admins -->
    <div class="bg-slate-950 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900 text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Subdomain</th>
                        <th class="px-6 py-4">Tenant Access URL</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($admins as $admin)
                        @php
                            $centralDomain = env('APP_CENTRAL_DOMAIN', 'localhost');
                            $tenantUrl = "http://{$admin->subdomain}.{$centralDomain}:8000";
                            $tenantAdminUrl = "http://{$admin->subdomain}.{$centralDomain}:8000/admin/login";
                        @endphp
                        <tr class="hover:bg-slate-900/50 transition">
                            <td class="px-6 py-4 font-mono text-xs text-slate-500">#{{ $admin->id }}</td>
                            <td class="px-6 py-4 font-bold text-white">{{ $admin->name }}</td>
                            <td class="px-6 py-4 text-slate-400">{{ $admin->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-mono font-bold text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-md">
                                    {{ $admin->subdomain }}
                                </span>
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                <a href="{{ $tenantUrl }}" class="text-xs text-indigo-400 hover:underline" target="_blank">
                                    Frontend Blog &rarr;
                                </a>
                                <a href="{{ $tenantAdminUrl }}" class="text-xs text-emerald-400 hover:underline" target="_blank">
                                    Admin Login &rarr;
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-xs font-bold text-emerald-400 bg-emerald-500/10 rounded-full">
                                    Active
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500 text-sm">
                                No tenant subdomains created yet. Click "+ Create New Tenant Admin" above.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
