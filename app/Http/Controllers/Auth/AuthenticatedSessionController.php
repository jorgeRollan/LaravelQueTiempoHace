<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Services\RecaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
class AuthenticatedSessionController extends Controller
{


    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render("Auth/Login", [
            "canResetPassword" => Route::has("password.request"),
            "status" => session("status"),
        ]);
    }
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request, RecaptchaService $recaptcha)
{
    // valido los datos del formulario
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'recaptcha_token' => 'required',
    ]);

    // validar el token de reCAPTCHA
    if (!$recaptcha->validate($request->input('recaptcha_token'))) {
        return response()->json(["message" => "reCAPTCHA validation failed"], 422);
    }

    // intentar autenticar el usuario
    if (Auth::attempt($request->only('email', 'password'))) {
        // genero la sesión
        $request->session()->regenerate();
        return response()->json([
            "message" => "Login successful",
            "user" => Auth::user(),
        ]);
    }

    return response()->json(["message" => "Credenciales incorrectas"], 401);
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        // cierra la sesión mediante el guarda web
        Auth::guard("web")->logout();
        // invalida la sesión
        $request->session()->invalidate();
        // regenera el token de la sesión
        $request->session()->regenerateToken();
        return response()->json([
            "message" => "Sesión cerrada",
        ]);
    }

    /**
     * Check if the user is authenticated.
     */
    public function check(): JsonResponse
    {
        // verifica si el usuario está autenticado
        if (Auth::check()) {
            return response()->json([
                "authenticated" => true,
                "user" => Auth::user(),
            ]);
        }

        return response()->json(["authenticated" => false]);
    }
}
