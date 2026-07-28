# Laravel Dynamic Subdomain Architecture Plan (Live Shared Hosting / cPanel Setup)

This document provides a production-ready implementation plan for a **Laravel Multi-Tenant Subdomain Blog System** specifically designed for deployment on **Shared Hosting (cPanel / DirectAdmin)** with **Cloudflare / cPanel DNS integration**.

---

## 🎯 High-Level Workflow

1. **SuperAdmin Panel (`admin.yourdomain.com`)**:
   - SuperAdmin fills out a form: Name, Email, Password, and **Subdomain** (e.g. `tech`, `travel`, `university1`).
   - Clicking "Save" inserts a new Admin record into MySQL with `subdomain = 'tech'`.

2. **Shared Hosting & DNS Setup**:
   - In cPanel / Cloudflare DNS, a **Wildcard Subdomain (`*.yourdomain.com`)** or individual Subdomains are created, pointing to the Laravel `/public` folder.
   - All subdomain traffic (`tech.yourdomain.com`, `travel.yourdomain.com`, `admin.yourdomain.com`) hits the **same single Laravel app instance**.

3. **Runtime Routing & Dynamic Resolution**:
   - When a user visits `https://tech.yourdomain.com`:
     - Middleware extracts `tech` from `req.getHost()`.
     - Queries DB for `User` where `subdomain = 'tech'`.
     - Automatically filters all Blogs so **only** `tech`'s blogs are retrieved and displayed on `tech.yourdomain.com`.

---

## 🌐 1. Live Shared Hosting & DNS Configuration (cPanel / Cloudflare)

### A. Cloudflare / cPanel DNS Setup
To allow dynamically created subdomains to work instantly on your live server without adding a new DNS record every single time:

1. Go to your DNS Manager (Cloudflare / GoDaddy / cPanel Zone Editor).
2. Add a **Wildcard A Record**:
   - **Type:** `A`
   - **Name:** `*` (or `*.yourdomain.com`)
   - **IPv4 Address:** Your Shared Hosting Server IP.
   - **TTL:** Auto

3. Add SuperAdmin Record (Optional if wildcard is used):
   - **Type:** `A` or `CNAME`
   - **Name:** `admin` ➔ Points to Server IP.

### B. cPanel Subdomain Document Root Setup
1. Log in to **cPanel** ➔ Go to **Subdomains**.
2. Create Subdomain: `*` (Wildcard).
3. Set **Document Root** to point to your Laravel project's `public` directory:
   - `public_html/your-project/public` (or `public_html` if root redirect is configured).

---

## 🗄️ 2. Database Design & Migration (Laravel)

### `users` Migration
```php
// database/migrations/2026_01_01_000000_create_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['superadmin', 'admin'])->default('admin');
            $table->string('subdomain')->nullable()->unique(); // e.g., 'tech', 'travel'
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

### `blogs` Migration
```php
// database/migrations/2026_01_01_000001_create_blogs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('slug');
            $table->text('content');
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
```

---

## ⚙️ 3. Laravel Tenant Middleware (`IdentifyTenant.php`)

Create the tenant resolution middleware: `php artisan make:middleware IdentifyTenant`

```php
namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost(); // e.g. "tech.yourdomain.com"
        
        // Remove port if testing locally
        $hostOnly = explode(':', $host)[0];
        $hostParts = explode('.', $hostOnly);

        // Assuming domain format: subdomain.domain.com (3 parts)
        // If domain is tech.yourdomain.com => $hostParts[0] = 'tech'
        if (count($hostParts) >= 3) {
            $subdomain = strtolower($hostParts[0]);

            // Skip check for 'admin' (SuperAdmin domain) or 'www'
            if (!in_array($subdomain, ['admin', 'www'])) {
                $tenant = User::where('subdomain', $subdomain)
                              ->where('role', 'admin')
                              ->where('is_active', true)
                              ->first();

                if (!$tenant) {
                    abort(404, "Tenant subdomain [{$subdomain}] does not exist or is inactive.");
                }

                // Bind the current tenant into Laravel Service Container & Request
                app()->instance('currentTenant', $tenant);
                $request->attributes->set('tenant', $tenant);
            }
        }

        return $next($next);
    }
}
```

Register in `bootstrap/app.php` (Laravel 13):
```php
$middleware->alias([
    'identify.tenant' => \App\Http\Middleware\IdentifyTenant::class,
    'role' => \App\Http\Middleware\CheckRole::class,
]);
```

---

## 🔒 4. Automatic Data Isolation (Global Scope in `Blog.php`)

Attach an Eloquent Global Scope to the `Blog` Model. Whenever queries run, Laravel automatically appends `WHERE user_id = {tenant_id}`.

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Blog extends Model
{
    protected $fillable = ['user_id', 'title', 'slug', 'content', 'status'];

    protected static function booted(): void
    {
        // 1. Global Filter for Tenant
        static::addGlobalScope('tenantScope', function (Builder $builder) {
            if (app()->bound('currentTenant')) {
                $tenant = app('currentTenant');
                $builder->where('user_id', $tenant->id);
            }
        });

        // 2. Auto-assign user_id on Blog Creation
        static::creating(function ($blog) {
            if (app()->bound('currentTenant') && !$blog->user_id) {
                $blog->user_id = app('currentTenant')->id;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

## 🛣️ 5. Subdomain Routing Setup (`routes/web.php`)

Define dynamic routes using Laravel domain matching:

```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TenantAdminController;
use App\Http\Controllers\PublicBlogController;

// Change 'yourdomain.com' to your actual live domain in .env (e.g. env('APP_CENTRAL_DOMAIN', 'yourdomain.com'))

$centralDomain = env('APP_CENTRAL_DOMAIN', 'yourdomain.com');

// =========================================================================
// 1. SUPERADMIN ROUTES (e.g. admin.yourdomain.com)
// =========================================================================
Route::domain('admin.' . $centralDomain)->group(function () {
    Route::get('/login', [SuperAdminController::class, 'showLoginForm'])->name('superadmin.login');
    Route::post('/login', [SuperAdminController::class, 'login']);

    Route::middleware(['auth', 'role:superadmin'])->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
        
        // SuperAdmin adds Admin & assigns Subdomain
        Route::get('/admins/create', [SuperAdminController::class, 'createAdmin'])->name('superadmin.admins.create');
        Route::post('/admins', [SuperAdminController::class, 'storeAdmin'])->name('superadmin.admins.store');
    });
});

// =========================================================================
// 2. TENANT / SUBDOMAIN ROUTES ({subdomain}.yourdomain.com)
// =========================================================================
Route::domain('{subdomain}.' . $centralDomain)->middleware(['identify.tenant'])->group(function () {

    // A. Public Blog Frontend (Shows only this subdomain's blogs)
    Route::get('/', [PublicBlogController::class, 'index'])->name('tenant.public.home');
    Route::get('/blog/{slug}', [PublicBlogController::class, 'show'])->name('tenant.public.single');

    // B. Tenant Admin Panel (Only for this subdomain's admin)
    Route::prefix('admin')->group(function () {
        Route::get('/login', [TenantAdminController::class, 'showLoginForm'])->name('tenant.admin.login');
        Route::post('/login', [TenantAdminController::class, 'login']);

        Route::middleware(['auth', 'role:admin'])->group(function () {
            Route::get('/dashboard', [TenantAdminController::class, 'dashboard'])->name('tenant.admin.dashboard');
            Route::get('/blogs/create', [TenantAdminController::class, 'createBlog'])->name('tenant.admin.blogs.create');
            Route::post('/blogs', [TenantAdminController::class, 'storeBlog'])->name('tenant.admin.blogs.store');
        });
    });
});
```

---

## 👨‍💻 6. Controller Implementation

### SuperAdmin Controller (`SuperAdminController.php`)
```php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->latest()->get();
        return view('superadmin.dashboard', compact('admins'));
    }

    public function createAdmin()
    {
        return view('superadmin.create_admin');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'subdomain' => 'required|alpha_dash|unique:users,subdomain',
        ]);

        $subdomain = Str::lower($request->subdomain);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'subdomain' => $subdomain,
            'is_active' => true,
        ]);

        return redirect()->route('superadmin.dashboard')
            ->with('success', "Admin created! Access URL: https://{$subdomain}." . env('APP_CENTRAL_DOMAIN'));
    }
}
```

### Public Blog Frontend Controller (`PublicBlogController.php`)
```php
namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class PublicBlogController extends Controller
{
    public function index()
    {
        $tenant = app('currentTenant');

        // Global scope automatically fetches ONLY this tenant's blogs
        $blogs = Blog::where('status', 'published')->latest()->get();

        return view('tenant.public.home', compact('tenant', 'blogs'));
    }
}
```

---

## 🚀 7. Live Shared Hosting Setup Checklist

1. **Upload Code:** Upload your Laravel code to `/public_html/myproject` or root.
2. **Environment `.env` Settings:**
   ```env
   APP_URL=https://yourdomain.com
   APP_CENTRAL_DOMAIN=yourdomain.com
   DB_DATABASE=your_cpanel_dbname
   DB_USERNAME=your_cpanel_dbuser
   DB_PASSWORD=your_cpanel_dbpass
   ```
3. **Primary Domain Symlink / `.htaccess` (if placing outside public_html):**
   Place this `.htaccess` in your root `public_html` directory if your Laravel code is inside `public_html/laravel`:
   ```htaccess
   <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteRule ^$ public/ [L]
       RewriteRule (.*) public/$1 [L]
   </IfModule>
   ```

---

## 🧪 8. Live Testing Steps

1. Visit **`https://admin.yourdomain.com/login`** ➔ Log in as SuperAdmin.
2. Fill Form:
   - **Name:** Tech Admin
   - **Email:** `tech@test.com`
   - **Subdomain:** `tech`
3. Visit **`https://tech.yourdomain.com/admin/login`** ➔ Log in as `tech@test.com` and add a Blog *"Laravel Multi-Tenancy Guide"*.
4. Open **`https://tech.yourdomain.com/`** in an Incognito window ➔ **Result:** You will see *"Laravel Multi-Tenancy Guide"*.
5. Repeat by adding a second admin with subdomain `travel` ➔ Open **`https://travel.yourdomain.com/`** ➔ **Result:** `tech`'s blogs will **NOT** be visible on `travel.yourdomain.com`.
