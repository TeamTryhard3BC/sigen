import { BASE_PATH } from '../BASE_PATH.js';

$("#btnEnviar").click((event) => {
    event.preventDefault();

    let formData = new FormData();
    formData.append("fileName", "personalizacion1");
    formData.append("logo", $('#elegirLogo')[0].files[0]);
    formData.append("nombre", $('#elegirNombre').val());

    let url = BASE_PATH + '/negocio/modules/requests.php'
    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
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
                let url = BASE_PATH + `/presentacion/html/${language}/personalizacion2.html`

                window.location = url;
            }
        },
        error: function(data) {
            console.log(data);
        }
    })
});