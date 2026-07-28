@extends('layouts.app')

@section('title', 'Create Blog Post')
@section('brand', $tenant->name . ' Admin')

@section('nav_links')
    <a href="{{ route('tenant.admin.dashboard', ['subdomain' => $tenant->subdomain]) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
        &larr; Back to Dashboard
    </a>
@endsection

@section('content')
<div class="max-w-2xl mx-auto py-4">
    <div class="bg-white border border-slate-200/80 rounded-2xl p-8 space-y-6 shadow-xl shadow-slate-200/50">
        <div class="border-b border-slate-100 pb-4">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Create New Blog Post</h2>
            <p class="text-xs text-slate-500 mt-1">Publish content for {{ $tenant->name }} ({{ $tenant->subdomain }}.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }})</p>
        </div>

        <form method="POST" action="{{ route('tenant.admin.blogs.store', ['subdomain' => $tenant->subdomain]) }}" class="space-y-5">
            @csrf

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Blog Title</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. 10 Tips for Modern Web Development" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition duration-150">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Status</label>
                <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition duration-150">
                    <option value="published" selected>Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Content</label>
                <textarea name="content" rows="8" placeholder="Write your blog article content here..." required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition duration-150">{{ old('content') }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('tenant.admin.dashboard', ['subdomain' => $tenant->subdomain]) }}" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition duration-150 shadow-md shadow-indigo-600/20">
                    Publish Blog Post
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
