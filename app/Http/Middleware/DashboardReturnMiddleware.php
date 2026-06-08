<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\RedirectResponse;

class DashboardReturnMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Define the routes that represent the main list views.
        // If the user navigates directly to these routes (GET), we clear the dashboard context.
        $listRoutes = [
            route('pedidos'),
            route('pedidos_cancelados'),
            route('requisicoes'),
            route('compras'),
            route('finalizados')
        ];

        if ($request->isMethod('GET')) {
            $currentUrl = explode('?', url()->current())[0];
            if (in_array($currentUrl, $listRoutes)) {
                session()->forget('url_retorno');
            }
        }

        $response = $next($request);

        // If the response is a redirect to a list route, and we are in a dashboard context (url_retorno),
        // we override the redirect target to return the user to the exact dashboard view they were on.
        if ($response instanceof RedirectResponse) {
            $targetUrl = $response->getTargetUrl();
            $targetBaseUrl = explode('?', $targetUrl)[0];

            if (in_array($targetBaseUrl, $listRoutes)) {
                if (session()->has('url_retorno')) {
                    $response->setTargetUrl(session('url_retorno'));
                }
            }
        }

        return $response;
    }
}
