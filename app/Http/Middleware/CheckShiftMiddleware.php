<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Shift;

class CheckShiftMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user->hasRole(['admin', 'manager'])) {
            return $next($request);
        }

        if ($user->hasRole('cashier')) {
            $activeShift = Shift::where('user_id', $user->id)
                ->where('status', 'open')
                ->first();

            if (!$activeShift) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must open a shift first before processing orders'
                ], 403);
            }
            $request->merge(['current_shift' => $activeShift]);
        }

        return $next($request);
    }
}
