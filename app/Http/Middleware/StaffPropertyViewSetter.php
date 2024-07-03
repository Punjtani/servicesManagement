<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class StaffPropertyViewSetter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if($request->isMethod('get') && $user->staff && $user->staff->staff_role && $user->staff->client &&
            (!$user->staff->staff_roles->is_property_level))
        {
            Auth::user()->tmpAttributeSetter('id', Auth::user()->staff->client->user_id);
            Auth::user()->tmpAttributeSetter('client', Auth::user()->staff->client);
            Auth::user()->tmpAttributeSetter('staff', "");
        }
        return $next($request);
    }
}
