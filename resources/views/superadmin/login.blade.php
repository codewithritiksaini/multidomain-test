@extends('layouts.app')

@section('title', 'SuperAdmin Login')
@section('brand', 'Admin Portal')

@section('content')
<div class="max-w-md mx-auto py-12">
    <div class="bg-slate-950 border border-slate-800 rounded-2xl p-8 space-y-6 shadow-2xl">
        <div class="text-center space-y-2">
            <h2 class="text-2xl font-extrabold text-white">SuperAdmin Portal</h2>
            <p class="text-xs text-slate-400">Sign in to manage system administrators and subdomains</p>
        </div>

        <form method="POST" action="/login" class="space-y-4">
            @csrf

            <div class="space-y-1">
                <label class="block text-xs font-semibold text-slate-300">Email Address</label>
                <input type="email" name="email" value="{{ old('email', 'admin@example.com') }}" required autofocus
                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500 transition">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-semibold text-slate-300">Password</label>
                <input type="password" name="password" value="password" required
                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500 transition">
            </div>

            <div class="flex items-center justify-between text-xs text-slate-400">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-800 bg-slate-900 text-indigo-600 focus:ring-0">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition shadow-lg shadow-indigo-600/20">
                Sign In to SuperAdmin Panel
            </button>
        </form>
    </div>
</div>
@endsection
