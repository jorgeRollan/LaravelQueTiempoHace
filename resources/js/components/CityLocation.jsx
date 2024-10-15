import { useContext, useEffect, useState } from "react";
import FetchUrl from '../api/FetchUrl';
import ShowWeather from "./ShowWeather";
import DataContext from "../context/Contexts"
import DataFallback from "./DataFallback";

export default function CityLocation() {
  const [weatherData, setWeatherData] = useState(null);
  const [position, setPosition] = useState(null);
  const [errorLocation, setErrorLocation] = useState(null);
  const [errorWeatherData, setErrorWeatherData] = useState(null);
  const [loading, setLoading] = useState(false);

  //tiempo cada cuanto se recomprueba la ubicación
  const reloadLocationTime = 100000;

  const apiId = import.meta.env.VITE_OPENWEATHER_API_KEY;

  //posicion del navegador he puesto el setinterval para que actualice la ubicacion cada x ms(he puesto 10 de momento) llamo a la funcion primero para evitar el delay la primera vez(?)
  useEffect(() => {
    getLocation();
    const interval = setInterval(() => {
      getLocation();
    }, reloadLocationTime);
    return () => clearInterval(interval);
  }, []);
 
  //fetch de la ciudad del navegador
  useEffect(() => {
    if (position) {
      setLoading(!loading);
      const url = 'https://api.openweathermap.org/data/2.5/weather?lat=' + position.coords.latitude + '&lon=' + position.coords.longitude + '&appid=' + apiId + '&units=metric';
      FetchUrl(url, "GET", null, handleFetch);
    }
  }, [position]);


  // useffect para el estado error de localizacion
  useEffect(() => {
    if (errorLocation !== null) {
      window.alert(errorLocation.message);
    }
  }, [errorLocation]);

  // useffect para el estado error de datos
  useEffect(() => {
    if (errorWeatherData !== null) {
      window.alert(errorWeatherData.message);
    }
  }, [errorWeatherData]);

  //handle para la devolucion de datos del fetch o gestion de error
  const handleFetch = (newWeatherData) => {
    //si codigo http distinto de 200
    if (newWeatherData.cod !== 200) {
      setErrorWeatherData({ message: `error  ${newWeather.cod} No se han podido recuperar datos del tiempo` });
    }
    else {
      setWeatherData(DataFallback(newWeatherData));
    }
    //cargue los datos o falle hay que parar cargando
    setLoading(!loading);
  }

  //he sacado fuera la constante que estaba en el useffect para llamarla 2 veces con el setInterval
  const getLocation = () => {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (pos) => setPosition(pos),
        (error) => setErrorLocation(error)
      );
    } else {
      setErrorLocation({ message: "Geolocalización no soportada por el navegador" });
    }
  }

  //si cargando true muestro eso mientras
  if (!loading) {
    return (
      <h2>Devolviendo datos del servidor</h2>)
  }
  //si hay datos los muestro con un showWeather y su context
  if (weatherData) {
    return (
      <div>
        <DataContext.Provider value={weatherData}>
          <ShowWeather />
        </DataContext.Provider>
      </div>
    )
  }
}