<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\UserCitiesHistory;

class WeatherController extends Controller
{
    /**
     * Obtiene la información meteorológica por coordenadas geográficas
     *
     * @param Request $request el input con las coordenadas geográficas y las unidades
     * @return JsonResponse el resultado de la consulta
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getWeatherByLocation(Request $request)
    {
        // valida los datos del formulario 
        $validator = Validator::make($request->all(), [
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
        ]);

        // si hay errores, devuelve un mensaje de error
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $latitude = $request->input('lat');
        $longitude = $request->input('lon');
        $units = $request->input('units', 'metric');

        $apiKey = env('VITE_OPENWEATHER_API_KEY');
        $url = "https://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&appid={$apiKey}&units={$units}&lang=es";

        try {
            $response = Http::get($url);
            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => 'No puedo obtener los datos meteorológicos'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error del servidor'], 500);
        }
    }

    /**
     * Obtiene la información meteorológica por nombre de la ciudad
     *
     * @param Request $request el input con el nombre de la ciudad y las unidades
     * @return JsonResponse el resultado de la consulta
     */

    public function getWeatherByCity(Request $request)
    {
        $selectCity = $request->input('selectCity');
        $units = $request->input('units', 'metric');

        $apiKey = env('VITE_OPENWEATHER_API_KEY');
        $url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($selectCity) . "&appid={$apiKey}&units={$units}&lang=es";

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $weatherData = $response->json();

                // Si el usuario está autenticado, guardar en la base de datos la ciudad
                if (Auth::check()) {
                    $UserCitiesHistory = new UserCitiesHistory;
                    $UserCitiesHistory->user_id = auth()->id();
                    $UserCitiesHistory->name_city = $request->input('selectCity');
                    $UserCitiesHistory->name_long_city = $request->input('selectCity');
                    $UserCitiesHistory->save();
                }

                // Devolver la respuesta del clima
                return response()->json($weatherData);
            } else {
                return response()->json(['error' => 'No puedo obtener los datos meteorológicos'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error del servidor: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Obtiene la previsión del tiempo por días de las coordenadas pedidas
     * 
     * @param Request $request el input con las coordenadas de la ciudad y las unidades
     * @return JsonResponse el resultado de la consulta
     */
    public function getWeatherForecast(Request $request)
    {
        $selectCity = $request->input('selectCity');
        $latitude = $request->input('lat');
        $longitude = $request->input('lon');
        $units = $request->input('units', 'metric'); // Default to 'metric'
        $apiKey = env('VITE_OPENWEATHER_API_KEY');

        if ($selectCity) {
            // como utilizo este método para ciudades por coordenadas o por nombre, si me viene por nombre lo convierto a coordenadas con la api
            $geoUrl = "http://api.openweathermap.org/geo/1.0/direct?q=" . urlencode($selectCity) . "&limit=1&appid={$apiKey}&lang=es";

            try {
                $geoResponse = Http::get($geoUrl);
                if ($geoResponse->failed() || empty($geoResponse->json())) {
                    return response()->json(['error' => 'No puedo conseguir las coordenadas de la ciudad'], 400);
                }

                // cojo la primera ciudad que devuelva
                $cityData = $geoResponse->json()[0];
                $latitude = $cityData['lat'];
                $longitude = $cityData['lon'];
            } catch (\Exception $e) {
                return response()->json(['error' => 'Geocoding error: ' . $e->getMessage()], 500);
            }
        }

        if (!$latitude || !$longitude) {
            return response()->json(['error' => 'Las coordenadas son requeridas si no se proporciona el nombre de la ciudad'], 400);
        }

        // ya si llamo a la api para pedir el forecast
        $forecastUrl = "https://pro.openweathermap.org/data/2.5/forecast/daily?lat={$latitude}&lon={$longitude}&appid={$apiKey}&units={$units}&cnt=16&lang=es";

        try {
            $response = Http::get($forecastUrl);
            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => 'No puedo obtener los datos meteorológicos'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * funcion para el mapa de los paises, como no deja buscar por nombre de pais directamente, busco por nombre de ciudad y luego busco por el país
     * uso una combinacion de la capital y el pais y obtengo las coordenadas de esa ciudad
     * para situar el mapa a ese país
     *
     * @param Request $request el input con el nombre de la ciudad y el país
     * @return JsonResponse el resultado de la consulta
     */
    public function getLocationCountry(Request $request)
    {
        $apiKey = env('VITE_OPENWEATHER_API_KEY');
        $selectCity = $request->input('selectCity');
        $selectCountry = $request->input('selectCountry');
        if ($selectCountry && $selectCity) {
            $geoUrl = "http://api.openweathermap.org/geo/1.0/direct?q=" . urlencode($selectCity) . "," . urlencode($selectCountry) . "&limit=1&appid={$apiKey}";

            try {
                $geoResponse = Http::get($geoUrl);
                if ($geoResponse->failed() || empty($geoResponse->json())) {
                    return response()->json(['error' => 'No puedo conseguir las coordenadas del país'], 400);
                }

                // cojo la primera ciudad que devuelva
                $countryData = $geoResponse->json()[0];
                $latitude = $countryData['lat'];
                $longitude = $countryData['lon'];
                return response()->json([
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Geocoding error: ' . $e->getMessage()], 500);
            }
        }
    }

    /**
     * Obtiene la previsión del tiempo por horas de  las coordenadas pedidas
     * 
     * @param Request $request el input con las coordenadas de la ciudad y las unidades
     * @return JsonResponse el resultado de la consulta
     */
    public function getWeatherForecastHourly(Request $request)
    {
        $selectCity = $request->input('selectCity');
        $latitude = $request->input('lat');
        $longitude = $request->input('lon');
        $units = $request->input('units', 'metric'); // Default to 'metric'
        $apiKey = env('VITE_OPENWEATHER_API_KEY');

        if ($selectCity) {
            $geoUrl = "http://api.openweathermap.org/geo/1.0/direct?q=" . urlencode($selectCity) . "&limit=1&appid={$apiKey}&lang=es";

            try {
                $geoResponse = Http::get($geoUrl);
                if ($geoResponse->failed() || empty($geoResponse->json())) {
                    return response()->json(['error' => 'No puedo conseguir las coordenadas de la ciudad'], 400);
                }

                // cojo la primera ciudad que devuelva
                $cityData = $geoResponse->json()[0];
                $latitude = $cityData['lat'];
                $longitude = $cityData['lon'];
            } catch (\Exception $e) {
                return response()->json(['error' => 'Geocoding error: ' . $e->getMessage()], 500);
            }
        }

        if (!$latitude || !$longitude) {
            return response()->json(['error' => 'Las coordenadas son requeridas si no se proporciona el nombre de la ciudad'], 400);
        }

        $forecastUrl = "https://pro.openweathermap.org/data/2.5/forecast/hourly?lat={$latitude}&lon={$longitude}&appid={$apiKey}&units={$units}&lang=es";

        try {
            $response = Http::get($forecastUrl);
            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => 'Unable to fetch weather data'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
}
