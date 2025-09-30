<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Força o header 'Accept' para 'application/json' na requisição.
        // Isso garante que o Laravel sempre use a resposta JSON para erros
        // de validação e outras exceções.
        $request->headers->set('Accept', 'application/json');

        // 2. Continua o processamento da requisição.
        return $next($request);
    }
}