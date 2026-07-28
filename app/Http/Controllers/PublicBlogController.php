<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class PublicBlogController extends Controller
{
    public function index()
    {
        $tenant = app('currentTenant');

        // Global scope automatically filters queries to user_id = $tenant->id
        $blogs = Blog::where('status', 'published')->latest()->get();

        return view('tenant.public.home', compact('tenant', 'blogs'));
    }

    public function show($subdomain, $slug)
    {
        $tenant = app('currentTenant');

        // Global scope automatically filters queries to user_id = $tenant->id
        $blog = Blog::where('slug', $slug)->where('status', 'published')->firstOrFail();

        return view('tenant.public.single', compact('tenant', 'blog'));
    }
}
