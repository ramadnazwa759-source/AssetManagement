<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AssetManagementAuth
{
    /**
     * Memeriksa autentikasi khusus Asset Management.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        /*
         * VR-AM-ACCESS-001
         * VR-AM-ACCESS-002
         * VR-AM-ACCESS-003
         * VR-AM-ACCESS-004
         * VR-AM-AUTH-007
         */
        if (!$request->session()->get('asset_management_authenticated')) {
            return redirect()->route('auth.login');
        }

        return $next($request);
    }
}