<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // Cek jika permintaan tidak mengharapkan JSON
        if (! $request->expectsJson()) {
            // Jika URL dimulai dengan 'panel', arahkan ke login admin
            if ($request->is('panel/*')) {
                return route('admin.login');
            } else{
                return route('login');
            }  
        }
    }
}
