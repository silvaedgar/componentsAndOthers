<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Session\Store;

class LogSessionTimeOut
{
    protected $session;
    public function __construct(Store $session)
    {
        $this->session = $session;
        //
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       $response = $next($request);

        // Verificar si la sesión ha sido invalidada (indicativo de timeout)
        if (!Auth::check() && $this->session->isStarted() && !$this->session->isValidId($this->session->getId())) {
            Log::info('Sesión cerrada automáticamente por inactividad.', [
                'session_id' => $this->session->getId(),
                'user_id' => Auth::id(), // Esto podría ser null si la sesión ya se invalidó
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);
        }

        return $response;
    }
}
