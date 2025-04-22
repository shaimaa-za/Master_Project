<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            // إذا كان المستخدم "admin"، أعد توجيهه إلى لوحة تحكم الأدمن
            return redirect()->route('filament.admin');
        }

        // السماح بالوصول إلى الطلب إذا لم يكن المستخدم "admin"
        return $next($request);
    }

    
}
