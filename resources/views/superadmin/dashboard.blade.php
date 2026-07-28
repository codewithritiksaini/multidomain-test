@extends('layouts.app')

@section('title', 'SuperAdmin Dashboard')
@section('brand', 'SuperAdmin Panel')

@section('nav_links')
    <span class="text-xs text-slate-500 font-medium bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
        {{ auth()->user()->email }}
    </span>
    <form method="POST" action="{{ route('superadmin.logout') }}" class="inline">
        @csrf
        <button type="submit" class="px-3.5 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 rounded-lg transition duration-150">
            Logout
        </button>
    </form>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-200/80 pb-5">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tenant Subdomains Directory</h1>
            <p class="text-xs text-slate-500 mt-1">Manage multi-tenant admins and dynamic subdomain bindings</p>
        </div>
        <a href="{{ route('superadmin.admins.create') }}" class="px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition duration-150 shadow-md shadow-indigo-600/20 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Create New Tenant Admin
        </a>
    </div>

    <!-- Table of Tenant Admins -->
    <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Subdomain</th>
                        <th class="px-6 py-4">Tenant Access URL</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($admins as $admin)
                        @php
                            $centralDomain = env('APP_CENTRAL_DOMAIN', 'localhost');
                            $tenantUrl = "http://{$admin->subdomain}.{$centralDomain}:8000";
                            $tenantAdminUrl = "http://{$admin->subdomain}.{$centralDomain}:8000/login";
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition duration-150">
                            <td class="px-6 py-4 font-mono text-xs text-slate-400">#{{ $admin->id }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $admin->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $admin->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-mono font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-md">
                                    {{ $admin->subdomain }}.{{ $centralDomain }}
                                </span>
                            </td>
                            <td class="px-6 py-4 space-x-3">
                                <a href="{{ $tenantUrl }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline" target="_blank">
                                    Visit Blog &rarr;
                                </a>
                                <a href="{{ $tenantAdminUrl }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800 hover:underline" target="_blank">
                                    Tenant Login &rarr;
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-full">
                                    Active
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500 text-sm">
                                <div class="max-w-xs mx-auto space-y-2">
                                    <p class="font-medium text-slate-700">No tenant subdomains created yet.</p>
                                    <p class="text-xs text-slate-400">Click "Create New Tenant Admin" above to register your first tenant.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
