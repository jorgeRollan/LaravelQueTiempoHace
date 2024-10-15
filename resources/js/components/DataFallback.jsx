import * as Compass from 'cardinal-direction';

const DataFallback = (dataWeather) => {
    let { name, main: { temp, feels_like, humidity, pressure }, wind: { deg, speed }, weather } = dataWeather
    name ||= "Nombre no disponible";
    temp = temp ? `${Math.trunc(temp)} ºC` : "Temperatura no disponible";
    feels_like = feels_like ? `${Math.trunc(feels_like)} ºC` : "Sensación térmica no disponible";
    humidity = humidity ? `${humidity} %` : "Humedad no disponible";
    weather[0].description ??= "Descripción no disponible"
    deg = Compass.cardinalConverter(Compass.cardinalFromDegree(deg)) ?? "Dirección del viento no disponible"
    speed = speed ? `${speed} KM/H` : "Velocidad del viento no disponible"
    pressure = pressure ? `${pressure} Bares` : "Presión atmosférica no disponible"
    return { name, main: { temp, feels_like, humidity, pressure }, wind: { deg, speed }, weather }
}

export default DataFallback;