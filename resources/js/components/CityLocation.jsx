import { useEffect, useState } from "react";
import FetchUrl from '../api/FetchUrl';
import ShowWeather from "./ShowWeather";
import DataContext from "../context/Contexts"
import DataFallback from "./DataFallback";

export default function CityLocation() {
  const [weatherData, setWeatherData] = useState(null);
  const [position, setPosition] = useState(null);
  const [loading, setLoading] = useState(true);

  //tiempo cada cuanto se recomprueba la ubicación
  const reloadLocationTime = 100000;

  const apiId = import.meta.env.VITE_OPENWEATHER_API_KEY;

  //posicion del navegador he puesto el setinterval para que actualice la ubicacion cada x ms llamo a la funcion primero para evitar el delay la primera vez(?)
  useEffect(() => {
    getLocation();
    const interval = setInterval(() => {
      getLocation();
    }, reloadLocationTime);
    return () => clearInterval(interval);
  }, []);

  //useEffect para hacer fetch cuando cambio ubicacion
  useEffect(() => {
    if (position) {
      setLoading(true);
      const url = 'https://api.openweathermap.org/data/2.5/weather?lat=' + position.coords.latitude + '&lon=' + position.coords.longitude + '&appid=' + apiId + '&units=metric';
      FetchUrl(url, "GET", null, handleFetch);
    }
  }, [position]);

  //handle para la devolucion de datos del fetch
  const handleFetch = (newWeatherData) => {
    setWeatherData(DataFallback(newWeatherData));
    setLoading(false);
  }

  //obtener la localizacion del navegasor
  const getLocation = () => {
    try {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (pos) => setPosition(pos),
          (error) => { window.alert(`Error obteniendo la ubicación: ${error.message}`) }
        );
      } else {
        throw new Error("Geolocalización no soportada por el navegador");
      }
    } catch (error) { window.alert(error.message) }
  }

  //si cargando true muestro eso mientras carga
  if (loading) {
    return (
      <h2>Devolviendo datos del servidor</h2>)
  }

    return (
      <div>
        <DataContext.Provider value={weatherData}>
          <ShowWeather />
        </DataContext.Provider>
      </div>
    )
}