@extends('layouts.app')

@section('title', $tenant->name . ' - Blog')
@section('brand', $tenant->name)

@section('nav_links')
    <a href="{{ route('tenant.login', ['subdomain' => $tenant->subdomain]) }}" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition duration-150 shadow-md shadow-indigo-600/20">
        Login
    </a>
@endsection

@section('content')
<div class="space-y-10 max-w-4xl mx-auto">
    <!-- Subdomain Hero Banner -->
    <div class="p-8 rounded-3xl bg-gradient-to-r from-indigo-500 via-indigo-600 to-violet-600 text-white shadow-xl shadow-indigo-500/10 space-y-3 relative overflow-hidden">
        <span class="px-3 py-1 text-xs font-mono font-bold text-indigo-100 bg-white/15 border border-white/20 rounded-full inline-block">
            Subdomain: {{ $tenant->subdomain }}.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }}
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
            {{ $tenant->name }}
        </h1>
        <p class="text-indigo-100 text-sm max-w-xl">
            Welcome to {{ $tenant->name }}'s official publication space.
        </p>
    </div>

    <!-- Blog Posts List -->
    <div class="space-y-6">
        <div class="flex items-center justify-between border-b border-slate-200 pb-4">
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Latest Articles</h2>
            <span class="text-xs text-slate-500 font-medium">Published Posts</span>
        </div>

        @if($blogs->isEmpty())
            <div class="p-12 text-center border border-dashed border-slate-200 rounded-3xl bg-white shadow-xs space-y-3">
                <div class="inline-flex items-center justify-center h-12 w-12 rounded-2xl bg-indigo-50 text-indigo-600 font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                </div>
                <p class="text-slate-600 font-medium text-sm">No published articles yet for {{ $tenant->name }}.</p>
                <a href="{{ route('tenant.login', ['subdomain' => $tenant->subdomain]) }}" class="inline-block px-4 py-2 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
                    Log in to publish the first blog post &rarr;
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-5">
                @foreach($blogs as $blog)
                    <article class="p-7 bg-white border border-slate-200/80 hover:border-indigo-300 rounded-2xl shadow-xs hover:shadow-md transition duration-200 space-y-3 group">
                        <div class="flex items-center justify-between text-xs text-slate-500 font-medium">
                            <span class="text-indigo-600 font-semibold bg-indigo-50 px-2.5 py-0.5 rounded-md border border-indigo-100">{{ $tenant->name }}</span>
                            <time>{{ $blog->created_at->format('F d, Y') }}</time>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-indigo-600 transition duration-150">
                            <a href="{{ route('tenant.public.single', ['subdomain' => $tenant->subdomain, 'slug' => $blog->slug]) }}">
                                {{ $blog->title }}
                            </a>
                        </h3>
                        <p class="text-slate-600 text-sm line-clamp-2 leading-relaxed">
                            {{ Str::limit($blog->content, 180) }}
                        </p>
                        <div class="pt-2">
                            <a href="{{ route('tenant.public.single', ['subdomain' => $tenant->subdomain, 'slug' => $blog->slug]) }}" class="text-xs font-bold text-indigo-600 group-hover:text-indigo-700 inline-flex items-center gap-1">
                                Read Full Article &rarr;
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
