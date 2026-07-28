@extends('layouts.app')

@section('title', $blog->title . ' - ' . $tenant->name)
@section('brand', $tenant->name)

@section('nav_links')
    <a href="{{ route('tenant.public.home', ['subdomain' => $tenant->subdomain]) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
        &larr; Back to All Articles
    </a>
@endsection

@section('content')
<article class="max-w-3xl mx-auto py-4 space-y-8">
    <div class="space-y-4 border-b border-slate-200 pb-6">
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 text-xs font-mono font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-md">
                {{ $tenant->subdomain }}.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }}
            </span>
            <span class="text-xs text-slate-500 font-medium">• {{ $blog->created_at->format('F d, Y') }}</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight tracking-tight">
            {{ $blog->title }}
        </h1>
        <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
            <span>Author: <strong class="text-slate-800 font-semibold">{{ $tenant->name }}</strong></span>
        </div>
    </div>

    <!-- Article Content -->
    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-base whitespace-pre-line bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-xs">
        {{ $blog->content }}
    </div>

    <div class="pt-6 border-t border-slate-200 flex items-center justify-between">
        <a href="{{ route('tenant.public.home', ['subdomain' => $tenant->subdomain]) }}" class="px-4 py-2.5 text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition shadow-xs">
            &larr; Back to {{ $tenant->name }} Blog
        </a>
    </div>
</article>
@endsection
