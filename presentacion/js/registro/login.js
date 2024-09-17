$("#btnEnviar").click((event) => {
    event.preventDefault();

    $.ajax({
        url: 'http://localhost/proyectosigen/negocio/modules/requests.php',
        method: 'GET',
        data: {
            fileName: "login",
            tipoDocumento: $("#elegirTipoDocumento").val(),
            documento: $("#elegirNroDocumento").val(),
            contrasena: $("#elegirContrasena").val(),
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

                window.location = `http://localhost/proyectosigen/presentacion/html/${language}/index.html`;
            }
        },
        error: function(data) {
            console.log(data);
        }
    })
});