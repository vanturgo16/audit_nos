<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\AuditLogsTrait;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    use AuditLogsTrait;
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            //Audit Log
            $this->auditLogsShort('Try to access the manual link via the url box');
            Auth::logout();
            return redirect()->route('login')->with('fail','You do not have access to this page, Please Re-signin to prove its you');
        }

        return $next($request);
    }
}
