<?php

namespace App\Http\Middleware;

use App\Exceptions\PermissionDeniedException;
use Closure;

class CheckPermission
{
    public function handle($request, Closure $next, $permission = null)
    {
        if (auth()->user()->role->permissions()->where('slug',$permission)->first()){
            return $next($request);
        };

         throw new PermissionDeniedException();
    }
}
