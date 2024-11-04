import { getConfiguracionGimnasio } from "../funcionesUtiles.js";

$(document).ready(function() {
    let configuracionGimnasio;

    getConfiguracionGimnasio()
        .then((resultado) => {
            configuracionGimnasio = resultado
            console.log(configuracionGimnasio)
        })
        .catch((error) => {
            alert('Error al obtener configuracion del gimnasio: ' + error)
        });

    console.log(configuracionGimnasio);
});
