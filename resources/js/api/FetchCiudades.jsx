import axios from 'axios';

const FetchCiudades = async (url, method, header, handleFunction) => {
    // Llamada a la API para obtener las ciudades
    try {
        const response = await axios({
            url: 'http://localhost:8000/api/ciudades',
            method: method,
            header: header
        });
        if (await response.data.cod === 200) {
            handleFunction(response.data);
        }
        else throw new Error(`Error en la obtencion url ${response.data.cod}`);
    }
    catch (error) {
        window.alert(error.message);
    }
};

export default FetchCiudades;