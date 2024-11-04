import { BASE_PATH } from '../BASE_PATH.js';

$(() => {
    let url = BASE_PATH + '/negocio/modules/requests.php'
    $.ajax({
        url: url,
        method: 'POST',
        data: {
            fileName: "checkLogin",
        },
        success: function(data) {
            console.log(data);
            const retorno = JSON.parse(data);
            console.log(retorno);

            if (retorno == false) {
                const language = $("html").attr("lang");
                const respuesta = language == "spanish" && "¡Debes iniciar sesión primero!" || "You need to log-in first!";

                alert(respuesta);
                let url = BASE_PATH + `/presentacion/html/${language}/login.html`
                window.location = url;
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
});