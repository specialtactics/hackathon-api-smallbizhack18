<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HttpsProtocol
{
    public function handle(Request $request, Closure $next)
    {
        dump($request->isFromTrustedProxy());
        dump($proto = $request->getTrustedValues(Request::HEADER_X_FORWARDED_PROTO));

        dump(in_array(strtolower($proto[0]), array('https', 'on', 'ssl', '1'), true));

        dump($request->secure());
        dump(env('APP_ENV'));

        die;

        if (!$request->secure() && env('APP_ENV') === 'production') {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
