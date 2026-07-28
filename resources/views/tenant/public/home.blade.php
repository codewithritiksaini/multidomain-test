@extends('layouts.app')

@section('title', $tenant->name . ' — Publication')
@section('brand', $tenant->name)
@section('brand_icon', strtoupper(substr($tenant->name, 0, 1)))

@section('nav_links')
    <a href="{{ route('tenant.login', ['subdomain' => $tenant->subdomain]) }}" class="px-4 py-2 text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition duration-150 shadow-sm">
        Login
    </a>
@endsection

@section('content')
<div class="space-y-12 max-w-4xl mx-auto">
    <!-- Subdomain Hero Banner -->
    <div class="bg-white border border-slate-200/80 rounded-3xl p-8 sm:p-10 shadow-sm space-y-4">
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 text-xs font-mono font-semibold text-indigo-700 bg-indigo-50 border border-indigo-100 rounded-full">
                {{ $tenant->subdomain }}.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }}
            </span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
            {{ $tenant->name }}
        </h1>
        <p class="text-slate-500 text-base max-w-2xl leading-relaxed">
            Welcome to {{ $tenant->name }}'s official publication space. Discover latest insights, articles, and updates.
        </p>
    </div>

    <!-- Blog Posts Section -->
    <div class="space-y-6">
        <div class="flex items-center justify-between border-b border-slate-200/80 pb-4">
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Latest Articles</h2>
            <span class="text-xs text-slate-400 font-medium">{{ $blogs->count() }} {{ Str::plural('article', $blogs->count()) }}</span>
        </div>

        @if($blogs->isEmpty())
            <div class="p-12 text-center border border-dashed border-slate-200 rounded-3xl bg-white shadow-xs space-y-3">
                <div class="inline-flex items-center justify-center h-12 w-12 rounded-2xl bg-slate-100 text-slate-600 font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                </div>
                <p class="text-slate-600 font-semibold text-base">No published articles yet</p>
                <p class="text-slate-400 text-xs">Log in as admin to publish the first story for {{ $tenant->name }}.</p>
                <div class="pt-2">
                    <a href="{{ route('tenant.login', ['subdomain' => $tenant->subdomain]) }}" class="inline-flex items-center gap-1 px-4 py-2 text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition">
                        Log in to Publish &rarr;
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6">
                @foreach($blogs as $blog)
                    <article class="p-7 bg-white border border-slate-200/80 hover:border-slate-300 rounded-3xl shadow-xs hover:shadow-md transition duration-200 space-y-4 group">
                        <div class="flex items-center justify-between text-xs text-slate-400 font-medium">
                            <div class="flex items-center gap-2">
                                <div class="h-6 w-6 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-[10px]">
                                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                </div>
                                <span class="text-slate-700 font-semibold">{{ $tenant->name }}</span>
                            </div>
                            <time>{{ $blog->created_at->format('M d, Y') }}</time>
                        </div>

                        <div class="space-y-2">
                            <h3 class="text-2xl font-bold text-slate-900 group-hover:text-indigo-600 transition duration-150 tracking-tight leading-snug">
                                <a href="{{ route('tenant.public.single', ['subdomain' => $tenant->subdomain, 'slug' => $blog->slug]) }}">
                                    {{ $blog->title }}
                                </a>
                            </h3>
                            <p class="text-slate-600 text-sm line-clamp-3 leading-relaxed">
                                {{ Str::limit($blog->content, 220) }}
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('tenant.public.single', ['subdomain' => $tenant->subdomain, 'slug' => $blog->slug]) }}" class="text-xs font-bold text-slate-900 group-hover:text-indigo-600 inline-flex items-center gap-1.5 transition">
                                <span>Read Full Story</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                            <span class="text-[11px] text-slate-400 font-mono">Published</span>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
