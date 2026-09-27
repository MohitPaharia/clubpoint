<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClubMemberMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $club = $request->route('club');

        if (!$club->members()->where('user_id', auth()->id())->exists()) {
            abort(403, 'You are not a member of this Club!.');
        }
        
        return $next($request);
    }
}
