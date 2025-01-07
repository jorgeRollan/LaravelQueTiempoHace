<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\UserCities;
use App\Models\UserCitiesHistory;
use App\Http\Controllers\WeatherController;


// Rutas de consultas en la base de datos de la app, están todas las rutas auth por defecto aunque no uso la mayoría
// hago la distincion con el middleware auth de las rutas que necesitan autenticación y las que si
Route::middleware('guest')->group(function () {
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::post('register', [RegisteredUserController::class, 'store']);

    // rutas por defecto guest que no utilizo pero las dejo por si acaso
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [PasswordResetLinkController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [PasswordResetLinkController::class, 'store'])->name('password.update');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::get('/check-auth', [AuthenticatedSessionController::class, 'check']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::location(env('REACT_APP_CLIENT_URL') . '/dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');


    // rutas auth que si utilizo las de arriba las dejo por si acaso
    Route::post('/insertCiudad', function (Request $request) {
        $UserCities = new UserCities;
        $UserCities->name_city = $request->input('nameCity');
        $UserCities->name_long_city = $request->input('nameLongCity');
        $UserCities->user_id = auth()->id();
        $UserCities->save();

        return response()->json(['success' => true, 'message' => 'Ciudad guardada exitosamente']);
    });

    Route::post('/deleteCiudad', function (Request $request) {
        $userId = auth()->id();
        $nameCity = $request->input('nameCity');
        $UserCities = UserCities::where('user_id', $userId)
            ->where('name_city', $nameCity)
            ->first();

        if ($UserCities) {
            $UserCities->delete();
            return response()->json(['success' => true, 'message' => 'Ciudad eliminada exitosamente']);
        } else {
            return response()->json(['success' => false, 'message' => 'Ciudad no encontrada' . $userId . $nameCity], 404);
        }
    });

    Route::get('/ciudades', function () {
        $ciudades = UserCities::where('user_id', auth()->id())->get();

        if ($ciudades->isEmpty()) {
            return response()->json(['message' => 'No se encontraron ciudades favoritas'], 406);
        }

        return response()->json($ciudades, 200);
    });

    Route::get('/ciudadesHistory', function () {
        $ciudades = UserCitiesHistory::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        if ($ciudades->isEmpty()) {
            return response()->json(['message' => 'No se encontraron ciudades visitadas'], 406);
        }

        return response()->json($ciudades, 200);
    });

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
