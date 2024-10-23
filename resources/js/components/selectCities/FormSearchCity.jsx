import { useContext } from "react";

import SearchCityContext from "../../context/Contexts"


export default function FormSearchCities() {
    let { selectCity, setSelectCity, setLoading } = useContext(SearchCityContext);
    //handle para el campo de busqueda de la city
    const handleChange = (event) => {
        event.preventDefault();
        let value = document.getElementById("search").value;
        if (value !== selectCity) {
            setLoading(true);
            setSelectCity(value);
        }
    };

    return (
        <form>
            <label htmlFor="search"></label>
            <input type="text" id="search" name="search" placeholder="Introduzca una ciudad"></input>
            <button onClick={handleChange}>Buscar</button>
        </form>
    )
}