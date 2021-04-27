<?php


namespace App\Resolver;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class UserResolverForAudit implements \OwenIt\Auditing\Contracts\UserResolver
{
    /**
     * {@inheritdoc}
     */
    public static function resolve()
    {
        if( Auth::check() && Auth::guard('api')->check() ) {
            return Auth::guard('api')->user();
        }
        else {
            return null;
        }
    }
}
