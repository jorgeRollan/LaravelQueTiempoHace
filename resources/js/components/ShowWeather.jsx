import { useContext } from 'react';
import {DataContext} from "../context/Contexts"

const ShowWeather = () => {
  const weatherData = useContext(DataContext);
  let { name, main: { temp, feels_like, humidity, pressure }, wind: { deg, speed }, weather } = weatherData
  return (
    <div id="showWeather">
      <h2>Clima en {name}</h2>
      <p><strong>Temperatura:</strong> {temp}</p>
      <p><strong>Sensación Térmica:</strong> {feels_like}</p>
      <img src={`http://openweathermap.org/img/w/${weather[0].icon}.png`} alt="icono del tiempo no disponible" />
      <p><strong>Humedad:</strong> {humidity}</p>
      <p><strong>Descripción:</strong> {weather[0].description}</p>
      <p><strong>Direccion del viento:</strong> {deg}</p>
      <p><strong>Velocidad del viento:</strong> {speed}</p>
      <p><strong>Presion atmosférica:</strong> {pressure}</p>
    </div>
  )
}

export default ShowWeather;