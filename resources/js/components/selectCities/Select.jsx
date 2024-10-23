import { useContext } from "react";

import {SelectCityContext} from "../../context/Contexts"


export default function SelectCities() {
    let { selectCity, setSelectCity, selectCities, setLoading } = useContext(SelectCityContext);

    const handleChange = (event) => {
        let city = event.target.value;
        if (city !== selectCity) {
            setLoading(true);
            setSelectCity(city);
        }
    };

    return (
        <select name="select" value={selectCity} onChange={handleChange}>
            {selectCities === null ?
                <>
                    <option key='0' value="Madrid">Madrid</option>
                    <option key='1' value="Zaragoza">Zaragoza</option>
                    <option key='2' value="Huelva">Huelva</option>
                    <option key='3' value="Toledo">Toledo</option>
                    <option key='4' value="Murcia">Murcia</option>
                </>
                :
                
                    selectCities.map((option, index) => (
                        <>
                            <option key={index}>
                                {option.nombre}
                            </option>
                        </>
                    ))
            }
        </select>
    )
}