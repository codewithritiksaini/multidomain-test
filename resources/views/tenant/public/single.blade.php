@extends('layouts.app')

@section('title', $blog->title . ' - ' . $tenant->name)
@section('brand', $tenant->name)

@section('nav_links')
    <a href="{{ route('tenant.public.home', ['subdomain' => $tenant->subdomain]) }}" class="text-xs text-slate-400 hover:text-white transition">
        &larr; Back to All Articles
    </a>
@endsection

@section('content')
<article class="max-w-3xl mx-auto py-6 space-y-8">
    <div class="space-y-4 border-b border-slate-800 pb-6">
        <div class="flex items-center gap-3">
            <span class="px-2.5 py-0.5 text-xs font-mono font-bold text-purple-400 bg-purple-500/10 border border-purple-500/20 rounded-md">
                {{ $tenant->subdomain }}.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }}
            </span>
            <span class="text-xs text-slate-400">• {{ $blog->created_at->format('F d, Y') }}</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
            {{ $blog->title }}
        </h1>
        <div class="flex items-center gap-2 text-xs text-slate-400">
            <span>Author: <strong class="text-slate-200">{{ $tenant->name }}</strong></span>
        </div>
    </div>

    <!-- Article Content -->
    <div class="prose prose-invert max-w-none text-slate-300 leading-relaxed text-base whitespace-pre-line bg-slate-950/60 p-8 rounded-2xl border border-slate-800">
        {{ $blog->content }}
    </div>

    <div class="pt-6 border-t border-slate-800 flex items-center justify-between">
        <a href="{{ route('tenant.public.home', ['subdomain' => $tenant->subdomain]) }}" class="px-4 py-2 text-xs font-semibold text-white bg-slate-800 hover:bg-slate-700 rounded-xl transition">
            &larr; Back to {{ $tenant->name }} Blog
        </a>
    </div>
</article>
@endsection
