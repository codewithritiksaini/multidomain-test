@extends('layouts.app')

@section('title', 'SuperAdmin Login')
@section('brand', 'Admin Portal')

@section('content')
<div class="max-w-md mx-auto py-8">
    <div class="bg-white border border-slate-200/80 rounded-2xl p-8 space-y-6 shadow-xl shadow-slate-200/50">
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center h-12 w-12 rounded-2xl bg-indigo-50 text-indigo-600 font-bold mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">SuperAdmin Portal</h2>
            <p class="text-xs text-slate-500">Sign in to manage system administrators and subdomains</p>
        </div>

        <form method="POST" action="/login" class="space-y-4">
            @csrf

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Email Address</label>
                <input type="email" name="email" value="{{ old('email', 'admin@example.com') }}" required autofocus
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition duration-150">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-slate-700">Password</label>
                <input type="password" name="password" value="password" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition duration-150">
            </div>

            <div class="flex items-center justify-between text-xs text-slate-600">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 rounded-xl transition duration-150 shadow-md shadow-indigo-600/25">
                Sign In to SuperAdmin Panel
            </button>
        </form>
    </div>
</div>
@endsection
