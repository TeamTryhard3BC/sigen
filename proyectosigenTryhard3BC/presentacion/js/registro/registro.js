import { BASE_PATH } from '../BASE_PATH.js';

$("#btnEnviar").click((event) => {
    event.preventDefault();

    let url = BASE_PATH + '/negocio/modules/requests.php'
    $.ajax({
        url: url,
        method: 'POST',
        data: {
            fileName: "registro",
            nombre: $("#elegirNombre").val(),
            apellido: $("#elegirApellido").val(),
            tipoDocumento: $("#elegirTipoDocumento").val(),
            documento: $("#elegirNroDocumento").val(),
            fechaNacimiento: $("#elegirFechaNacimiento").val(),
            contrasena: $("#elegirContrasena").val()
        },
        success: function(data){
            console.log(data);
            const retorno = JSON.parse(data);
            console.log(retorno);

            let check = false;

            if(retorno) {
                $.each(retorno, (key, value) => {
                    if (!value) {
                        $(`#elegir${key}`).addClass("is-invalid");
                        $(`#elegir${key}`).tooltip('show');
                        check = true
                    } else {
                        $(`#elegir${key}`).removeClass("is-invalid");
                        $(`#elegir${key}`).tooltip('dispose');
                    }
                });
            }

            if (!check) {
                const language = $("html").attr("lang");
                let url = BASE_PATH + `/presentacion/html/${language}/index.html`

                window.location = url;
            }
        },
        error: function(data) {
            console.log(data);
        }
    })
});