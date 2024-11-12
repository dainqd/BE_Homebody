<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lang = $request->input('lang');
        switch ($lang) {
            case 'vi':
                Session::put('locale', 'vi');
                app()->setLocale('vi');
                break;
            case 'cn':
                Session::put('locale', 'cn');
                app()->setLocale('cn');
                break;
            default:
                Session::put('locale', 'en');
                app()->setLocale('en');
                break;
        }
        return $next($request);
    }
}
