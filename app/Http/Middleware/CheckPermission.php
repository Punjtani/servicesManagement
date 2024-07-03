<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\RolePermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission = null)
    {

        return $next($request);
        $userRole = Auth::user()->role;
        if ($this->checkUserPermsission($permission, $userRole)) {
            return $next($request);
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }

    public function checkUserPermsission($permission, $userRole)
    {
        $permissionId = Permission::where('slug', $permission)->select('id')->first();
        if(! $permissionId){
            return false;
        }
        $roleHasPermission = RolePermission::where('role_id', $userRole)->where('permission_id', $permissionId->id)->count();
        if ($roleHasPermission > 0) {
            return true;
        } else {
            return false;
        }
    }
}
