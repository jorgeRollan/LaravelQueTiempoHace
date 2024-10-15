import axios from 'axios';

const FetchCiudades = async (handleFunction) => {
    // Llamada a la API para obtener las ciudades
    try {
        const response = await axios.get('http://localhost:8000/api/ciudades')
        response.cod=200; 
        await handleFunction(response);
    }
    catch (error) {
        let cod = error.status;
        handleFunction({ cod });
    }
}

export default FetchCiudades;