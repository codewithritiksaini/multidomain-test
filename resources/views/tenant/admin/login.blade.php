@extends('layouts.app')

@section('title', 'Login - ' . optional($tenant)->name)
@section('brand', optional($tenant)->name ?? 'Login')

@section('nav_links')
    @if($tenant)
        <a href="http://{{ $tenant->subdomain }}.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }}:8000" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            &larr; Back to Blog
        </a>
    @endif
@endsection

@section('content')
<div class="max-w-md mx-auto py-8">
    <div class="bg-white border border-slate-200/80 rounded-2xl p-8 space-y-6 shadow-xl shadow-slate-200/50">
        <div class="text-center space-y-2">
            <span class="px-3 py-1 text-xs font-mono font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-md">
                {{ optional($tenant)->subdomain }}.{{ env('APP_CENTRAL_DOMAIN', 'localhost') }}
            </span>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Login</h2>
            <p class="text-xs text-slate-500">Sign in to manage {{ optional($tenant)->name ?? 'your account' }}</p>
        </div>

        <form method="POST" action="/login" class="space-y-4">
            @csrf

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition duration-150">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Password</label>
                <input type="password" name="password" placeholder="Enter your password" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition duration-150">
            </div>

            <button type="submit" class="w-full py-3 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 rounded-xl transition duration-150 shadow-md shadow-indigo-600/25">
                Sign In
            </button>
        </form>
    </div>
</div>
@endsection
