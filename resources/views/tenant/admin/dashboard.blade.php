@extends('layouts.app')

@section('title', 'Admin Dashboard — ' . $tenant->name)
@section('brand', $tenant->name . ' Admin')
@section('brand_icon', strtoupper(substr($tenant->name, 0, 1)))

@section('nav_links')
    <a href="http://{{ $tenant->subdomain }}.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }}:8000" target="_blank" class="text-xs font-semibold text-slate-700 hover:text-slate-900">
        View Public Site &rarr;
    </a>
    <form method="POST" action="{{ route('tenant.admin.logout', ['subdomain' => $tenant->subdomain]) }}" class="inline">
        @csrf
        <button type="submit" class="px-3.5 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 rounded-xl transition duration-150">
            Logout
        </button>
    </form>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-200/80 pb-5">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Articles Management</h1>
                <span class="px-3 py-1 text-xs font-mono font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 rounded-md">
                    {{ $tenant->subdomain }}.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">All posts are isolated to {{ $tenant->name }}</p>
        </div>
        <a href="{{ route('tenant.admin.blogs.create', ['subdomain' => $tenant->subdomain]) }}" class="px-4 py-2.5 text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition duration-150 shadow-sm inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Create New Article
        </a>
    </div>

    <!-- Blogs Table -->
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Slug</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Created At</th>
                        <th class="px-6 py-4">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($blogs as $blog)
                        <tr class="hover:bg-slate-50/70 transition duration-150">
                            <td class="px-6 py-4 font-mono text-xs text-slate-400">#{{ $blog->id }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $blog->title }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $blog->slug }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-bold capitalize {{ $blog->status === 'published' ? 'text-emerald-700 bg-emerald-50 border border-emerald-200' : 'text-amber-700 bg-amber-50 border border-amber-200' }} rounded-full">
                                    {{ $blog->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">{{ $blog->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('tenant.public.single', ['subdomain' => $tenant->subdomain, 'slug' => $blog->slug]) }}" target="_blank" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                                    View Story &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500 text-sm">
                                <div class="max-w-xs mx-auto space-y-2">
                                    <p class="font-medium text-slate-700">No blog posts found for this tenant.</p>
                                    <p class="text-xs text-slate-400">Click "Create New Article" to write your first story.</p>
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
