<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Blog extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'status',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // 1. Global Filter for Tenant Subdomain
        static::addGlobalScope('tenantScope', function (Builder $builder) {
            if (app()->bound('currentTenant')) {
                $tenant = app('currentTenant');
                $builder->where('user_id', $tenant->id);
            }
        });

        // 2. Auto-assign user_id on Blog Creation when operating within a tenant scope
        static::creating(function ($blog) {
            if (app()->bound('currentTenant') && !$blog->user_id) {
                $blog->user_id = app('currentTenant')->id;
            }
        });
    }

    /**
     * Get the user that owns the blog.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
