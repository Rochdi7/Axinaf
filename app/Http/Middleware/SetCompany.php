<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCompany
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // 🔐 Cas non connecté (devrait déjà être bloqué par 'auth')
        if (!$user) {
            return redirect()->route('login');
        }

        $routeName = optional($request->route())->getName();
        $isCompanyRoute = $routeName && str_starts_with($routeName, 'backoffice.companies.');

        // 🛡️ Cas 1 : Accès aux routes réservées à `superadmin` (ex: backoffice.companies.*)
        if ($isCompanyRoute) {
            if (!$user->hasRole('superadmin')) {
                $message = 'Accès refusé : cette section est réservée au superadmin.';

                return $request->expectsJson()
                    ? response()->json(['message' => $message], 403)
                    : abort(403, $message);
            }

            return $next($request);
        }

        // ✅ Cas 2 : superadmin → accès libre au reste du backoffice
        if ($user->hasRole('superadmin')) {
            return $next($request);
        }

        // 🚫 Cas 3 : admin ou client sans entreprise liée
        if (!$user->company_id) {
            $message = 'Votre compte n’est rattaché à aucune entreprise.';

            return $request->expectsJson()
                ? response()->json(['message' => $message], 403)
                : abort(403, $message);
        }

        // ✅ Cas 4 : admin ou client avec entreprise → accès autorisé
        return $next($request);
    }
}
