import { useEffect, useState, useContext } from "react";
import FetchUrl from "../../api/FetchUrl";
import ShowWeather from "../ShowWeather";
import FormSearchCity from "./FormSearchCity"
import Select from "./Select";
import FetchCiudades from "../../api/FetchCiudades";
import CleanContext from '../../context/Contexts';
import DataContext from "../../context/Contexts";
import SearchCityContext from "../../context/Contexts"
import SelectCityContext from "../../context/Contexts";
import DataFallback from "../DataFallback";


export default function SelectCities() {
  const [weatherData, setWeatherData] = useState(null);
  const [selectCity, setSelectCity] = useState("Toledo");
  const [selectCities, setSelectCities] = useState(null);
  const [fetchCiudades, setFetchCiudades] = useState(true);
  const [loading, setLoading] = useState(true);

  const { setClean } = useContext(CleanContext);

  const apiId = import.meta.env.VITE_OPENWEATHER_API_KEY;

  const handleFetch = (newWeatherData) => {
    setWeatherData(DataFallback(newWeatherData));
    setLoading(false);
    setClean(true);
  }

  const handleFetchCiudades = (newCities) => {
    setSelectCities(newCities.data);
  }

  //use effect para llamar a fetch cuando cambia el SelectCity
  useEffect(() => {
    if (selectCity !== null) {
      const url = 'https://api.openweathermap.org/data/2.5/weather?q=' + selectCity + '&appid=' + apiId + '&units=metric';
      FetchUrl(url, "GET", null, handleFetch);
    }
  }, [selectCity]);

  useEffect(() => {
      FetchCiudades(handleFetchCiudades);
      setFetchCiudades(false);
  }, [fetchCiudades]);

  return (
    <>
      <div id="selectCity">
        <>
          <SelectCityContext.Provider value={{ selectCity, setSelectCity,selectCities, loading, setLoading }}>
            <Select />
          </SelectCityContext.Provider>
        </>

        <>
          <SearchCityContext.Provider value={{ selectCity, setSelectCity, loading, setLoading }}>
            <FormSearchCity />
          </SearchCityContext.Provider>
        </>

        <DataContext.Provider value={weatherData}>
          {loading ? <h2>Devolviendo datos del servidor</h2> : <ShowWeather />}
        </DataContext.Provider>
      </div>
    </>
  )
}