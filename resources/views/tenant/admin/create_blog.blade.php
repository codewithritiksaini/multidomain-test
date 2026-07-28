@extends('layouts.app')

@section('title', 'Create Blog Post')
@section('brand', $tenant->name . ' Admin')

@section('nav_links')
    <a href="{{ route('tenant.admin.dashboard', ['subdomain' => $tenant->subdomain]) }}" class="text-xs text-slate-400 hover:text-white transition">
        &larr; Back to Dashboard
    </a>
@endsection

@section('content')
<div class="max-w-2xl mx-auto py-6">
    <div class="bg-slate-950 border border-slate-800 rounded-2xl p-8 space-y-6 shadow-2xl">
        <div class="border-b border-slate-800 pb-4">
            <h2 class="text-2xl font-extrabold text-white">Create New Blog Post</h2>
            <p class="text-xs text-slate-400">Publish content for {{ $tenant->name }} ({{ $tenant->subdomain }}.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }})</p>
        </div>

        <form method="POST" action="{{ route('tenant.admin.blogs.store', ['subdomain' => $tenant->subdomain]) }}" class="space-y-5">
            @csrf

            <div class="space-y-1">
                <label class="block text-xs font-semibold text-slate-300">Blog Title</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. 10 Tips for Modern Web Development" required
                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500 transition">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-semibold text-slate-300">Status</label>
                <select name="status" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500 transition">
                    <option value="published" selected>Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-semibold text-slate-300">Content</label>
                <textarea name="content" rows="8" placeholder="Write your blog article content here..." required
                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500 transition">{{ old('content') }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('tenant.admin.dashboard', ['subdomain' => $tenant->subdomain]) }}" class="px-4 py-2.5 text-xs font-semibold text-slate-400 hover:text-white transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition shadow-lg shadow-indigo-600/20">
                    Publish Blog Post
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
