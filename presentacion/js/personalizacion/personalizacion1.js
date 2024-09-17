$("#btnEnviar").click((event) => {
    event.preventDefault();

    $.ajax({
        url: 'http://localhost/proyectosigen/negocio/modules/requests.php',
        method: 'GET',
        data: {
            fileName: "personalizacion1",
            logo: $("#elegirLogo").val(),
            nombre: $("#elegirNombre").val(),
            parametros: $("#elegirParametros").val()
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

                window.location = `http://localhost/proyectosigen/presentacion/html/${language}/personalizacion2.html`;
            }
        },
        error: function(data) {
            console.log(data);
        }
    })
});