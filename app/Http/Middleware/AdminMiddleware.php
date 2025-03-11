<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = auth()->user();

        // This assumes that the user is guaranteed to exist because 'auth' middleware already runs before this
        if (!($user instanceof User) || !$user->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
