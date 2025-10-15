<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPendingInvoices
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            // Count user's pending invoices
            $pendingCount = $user->invoices()
                ->where('status', 'pending')
                ->count();

            if ($pendingCount > 0) {
                // Redirect to billing history with alert
                return redirect('/billing/history')
                    ->with('alert', "You have {$pendingCount} pending invoice(s). Please pay them before accessing reports.");
            }
        }

        return $next($request);
    }
}
