import { BASE_PATH } from '../BASE_PATH.js';

$("#cerrarSesion").click((event) => {
    event.preventDefault();

    let url = BASE_PATH + '/negocio/modules/requests.php'
    $.ajax({
        url: url,
        method: 'POST',
        data: {
            fileName: "cerrarSesion",
        },
        success: function (data) {
            console.log(data);
            const retorno = JSON.parse(data);
            console.log(retorno);

            if (retorno == true) {
                const language = $("html").attr("lang");
                const respuesta = language == "spanish" && "Cerraste sesión correctamente." || "Logged out successfully.";

                alert(respuesta);
                let url = BASE_PATH + `/presentacion/html/${language}/login.html`

                window.location = url;
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
});