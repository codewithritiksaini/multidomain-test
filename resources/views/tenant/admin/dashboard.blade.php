@extends('layouts.app')

@section('title', 'Tenant Admin Dashboard')
@section('brand', $tenant->name . ' Dashboard')

@section('nav_links')
    <a href="http://{{ $tenant->subdomain }}.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }}:8000" target="_blank" class="text-xs text-indigo-400 hover:underline">
        View Public Site &rarr;
    </a>
    <form method="POST" action="{{ route('tenant.admin.logout', ['subdomain' => $tenant->subdomain]) }}" class="inline">
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
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-extrabold text-white">Blog Management</h1>
                <span class="px-2.5 py-0.5 text-xs font-mono font-bold text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-md">
                    {{ $tenant->subdomain }}.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }}
                </span>
            </div>
            <p class="text-xs text-slate-400">All posts are isolated to this tenant using Eloquent Global Scope</p>
        </div>
        <a href="{{ route('tenant.admin.blogs.create', ['subdomain' => $tenant->subdomain]) }}" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition shadow-lg shadow-indigo-600/20">
            + Create New Blog Post
        </a>
    </div>

    <!-- Blogs Table -->
    <div class="bg-slate-950 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900 text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Slug</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Created At</th>
                        <th class="px-6 py-4">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($blogs as $blog)
                        <tr class="hover:bg-slate-900/50 transition">
                            <td class="px-6 py-4 font-mono text-xs text-slate-500">#{{ $blog->id }}</td>
                            <td class="px-6 py-4 font-bold text-white">{{ $blog->title }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-400">{{ $blog->slug }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-xs font-bold capitalize {{ $blog->status === 'published' ? 'text-emerald-400 bg-emerald-500/10' : 'text-amber-400 bg-amber-500/10' }} rounded-full">
                                    {{ $blog->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">{{ $blog->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('tenant.public.single', ['subdomain' => $tenant->subdomain, 'slug' => $blog->slug]) }}" target="_blank" class="text-xs text-indigo-400 hover:underline">
                                    View Post &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500 text-sm">
                                No blog posts found for this tenant. Click "+ Create New Blog Post" to add one!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
