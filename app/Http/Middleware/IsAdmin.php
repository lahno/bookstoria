<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()){
            $user = User::find(Auth::user()->id);
            foreach ($user->roles as $role)
            {
                if($role->name == 'admin'){
                    return $next($request);
                }else{
                    return redirect('/');
                }
            }

        }
        return redirect('/');

    }
}
