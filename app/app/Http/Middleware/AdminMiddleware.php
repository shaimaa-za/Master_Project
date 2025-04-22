<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            if (auth()->user()->role === 'admin') {
                return $next($request);
            }

            // إذا كان المستخدم زبونًا، قم بإعادة توجيهه إلى صفحة الـ dashboard الخاصة به
            return redirect()->route('customer.dashboard');
        }

        // إذا لم يكن المستخدم مسجل دخول، أعد توجيهه إلى صفحة تسجيل الدخول
        return redirect()->route('login');
    }
    
}
