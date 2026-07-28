@extends('layouts.app')

@section('title', $blog->title . ' — ' . $tenant->name)
@section('brand', $tenant->name)
@section('brand_icon', strtoupper(substr($tenant->name, 0, 1)))

@section('nav_links')
    <a href="{{ route('tenant.public.home', ['subdomain' => $tenant->subdomain]) }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
        &larr; Back to All Stories
    </a>
@endsection

@section('content')
<article class="max-w-3xl mx-auto py-4 space-y-8">
    <!-- Header -->
    <div class="space-y-4 border-b border-slate-200/80 pb-8">
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 text-xs font-mono font-semibold text-indigo-700 bg-indigo-50 border border-indigo-100 rounded-full">
                {{ $tenant->subdomain }}.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }}
            </span>
            <span class="text-xs text-slate-400 font-medium">• Published {{ $blog->created_at->format('F d, Y') }}</span>
        </div>

        <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 leading-tight tracking-tight">
            {{ $blog->title }}
        </h1>

        <div class="flex items-center gap-3 pt-2">
            <div class="h-9 w-9 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-xs">
                {{ strtoupper(substr($tenant->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-xs font-bold text-slate-900">{{ $tenant->name }}</p>
                <p class="text-[11px] text-slate-400">Author & Publisher</p>
            </div>
        </div>
    </div>

    <!-- Article Content -->
    <div class="bg-white border border-slate-200/80 p-8 sm:p-12 rounded-3xl shadow-xs text-slate-800 text-base leading-relaxed whitespace-pre-line space-y-4">
        {{ $blog->content }}
    </div>

    <!-- Back Footer -->
    <div class="pt-6 border-t border-slate-200/80 flex items-center justify-between">
        <a href="{{ route('tenant.public.home', ['subdomain' => $tenant->subdomain]) }}" class="px-5 py-2.5 text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition shadow-xs">
            &larr; More from {{ $tenant->name }}
        </a>
    </div>
</article>
@endsection
