<?php

namespace App\Http\Middleware;

use App\Http\Models\Integrator;
use Closure;

class BasicAuthIntegrators
{
    public function handle($request, Closure $next)
    {
        $auth =  str_replace('Basic ', '', $request->header('Authorization'));
        $auth = explode(':', base64_decode($auth));

        $integrator = Integrator::where("name", @$auth[0])
            ->where("password", @$auth[1])
            ->first();

        if (!@$integrator->id) {
            abort(401, 'Unauthorized');
        }

        $request->merge(["integrator" => $integrator]);
        return $next($request);
    }
}
