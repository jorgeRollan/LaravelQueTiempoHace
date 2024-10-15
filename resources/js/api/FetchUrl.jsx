import axios from 'axios'
//he puesto tres parametros de momento(no uso la cabecera de momento)
const FetchUrl = async (url, method, header, handleFunction) => {
  try {
    const response = await axios({
      url : url,
      method : method,
      header : header
    });
    await handleFunction(response.data);
  } 
  //asntes trataba aqui los errores del fetch pero los he propagado arriba para que los gestione el handle de select o ubicacion
  catch (error) {
      let cod = error.status;
      handleFunction({cod});
  }
};

export default FetchUrl;