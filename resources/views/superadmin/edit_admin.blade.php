@extends('layouts.app')

@section('title', 'Edit Tenant Admin — ' . $admin->name)
@section('brand', 'SuperAdmin Panel')
@section('brand_icon', 'A')

@section('nav_links')
    <a href="{{ route('superadmin.dashboard') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
        &larr; Back to Dashboard
    </a>
@endsection

@section('content')
<div class="max-w-xl mx-auto py-4">
    <div class="bg-white border border-slate-200/80 rounded-2xl p-8 space-y-6 shadow-xl shadow-slate-200/50">
        <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Tenant Admin</h2>
                <p class="text-xs text-slate-500 mt-1">Update details, subdomain, status, or password for #{{ $admin->id }}</p>
            </div>
            <span class="px-3 py-1 text-xs font-mono font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 rounded-md">
                {{ $admin->subdomain }}.{{ \App\Http\Middleware\IdentifyTenant::getParentDomain() }}
            </span>
        </div>

        <form method="POST" action="{{ route('superadmin.admins.update', $admin->id) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Admin Name</label>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition duration-150">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Admin Email</label>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition duration-150">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Subdomain Identifier</label>
                <div class="flex items-center gap-2">
                    <input type="text" name="subdomain" value="{{ old('subdomain', $admin->subdomain) }}" required
                        class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm font-mono focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition duration-150">
                    <span class="text-xs text-slate-500 font-mono font-bold">.{{ \App\Http\Middleware\IdentifyTenant::getParentDomain() }}</span>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">New Password <span class="text-slate-400 font-normal">(Leave blank to keep unchanged)</span></label>
                <input type="password" name="password" placeholder="Enter new password if changing"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition duration-150">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Status</label>
                <select name="is_active" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition duration-150">
                    <option value="1" {{ $admin->is_active ? 'selected' : '' }}>Active (Enabled)</option>
                    <option value="0" {{ !$admin->is_active ? 'selected' : '' }}>Inactive (Disabled)</option>
                </select>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('superadmin.dashboard') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition duration-150 shadow-md">
                    Update Tenant Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
