@extends('layouts.app')

@section('title', $tenant->name . ' - Blog')
@section('brand', $tenant->name)

@section('nav_links')
    <a href="http://{{ env('APP_CENTRAL_DOMAIN', 'localhost') }}:8000" class="text-xs text-slate-400 hover:text-white transition">
        &larr; Central Directory
    </a>
    <a href="{{ route('tenant.admin.login', ['subdomain' => $tenant->subdomain]) }}" class="px-3 py-1.5 text-xs font-semibold text-indigo-400 bg-indigo-500/10 hover:bg-indigo-500/20 rounded-lg transition">
        Admin Panel
    </a>
@endsection

@section('content')
<div class="space-y-10 max-w-4xl mx-auto">
    <!-- Subdomain Hero Banner -->
    <div class="p-8 rounded-3xl bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 border border-slate-800 space-y-3 relative overflow-hidden shadow-2xl">
        <span class="px-3 py-1 text-xs font-mono font-bold text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 rounded-full inline-block">
            Subdomain: {{ $tenant->subdomain }}.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }}
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
            {{ $tenant->name }}
        </h1>
        <p class="text-slate-400 text-sm">
            Welcome to the isolated subdomain publication. All content rendered here belongs strictly to {{ $tenant->name }}.
        </p>
    </div>

    <!-- Blog Posts List -->
    <div class="space-y-6">
        <h2 class="text-xl font-bold text-white border-b border-slate-800 pb-3">Latest Articles</h2>

        @if($blogs->isEmpty())
            <div class="p-8 text-center border border-dashed border-slate-800 rounded-2xl">
                <p class="text-slate-500 text-sm">No published articles yet for {{ $tenant->name }}.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($blogs as $blog)
                    <article class="p-6 bg-slate-950 border border-slate-800 hover:border-indigo-500/50 rounded-2xl transition space-y-3 group">
                        <div class="flex items-center justify-between text-xs text-slate-400">
                            <span>By {{ $tenant->name }}</span>
                            <time>{{ $blog->created_at->format('F d, Y') }}</time>
                        </div>
                        <h3 class="text-xl font-bold text-white group-hover:text-indigo-400 transition">
                            <a href="{{ route('tenant.public.single', ['subdomain' => $tenant->subdomain, 'slug' => $blog->slug]) }}">
                                {{ $blog->title }}
                            </a>
                        </h3>
                        <p class="text-slate-400 text-sm line-clamp-2">
                            {{ Str::limit($blog->content, 180) }}
                        </p>
                        <div>
                            <a href="{{ route('tenant.public.single', ['subdomain' => $tenant->subdomain, 'slug' => $blog->slug]) }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 inline-flex items-center gap-1">
                                Read Article &rarr;
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
