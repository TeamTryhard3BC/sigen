$("#btnEnviar").click((event) => {
    event.preventDefault();

    $.ajax({
        url: 'http://localhost/proyectosigen/negocio/modules/requests.php',
        method: 'GET',
        data: {
            fileName: "personalizacion2",
            descripcion: $("#elegirDescripcion").val(),
            ubicacion: $("#elegirUbicacion").val(),
            contacto: $("#elegirNumeroContacto").val(),
            instagram: $("#elegirInstagram").val(),
            mail: $("#elegirMail").val(),
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

                window.location = `http://localhost/proyectosigen/presentacion/html/${language}/personalizacion3.html`;
            }
        },
        error: function(data) {
            console.log(data);
        }
    })
});