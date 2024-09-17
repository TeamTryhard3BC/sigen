$(() => {
    const language = $("html").attr("lang");

    $('#cerrarSesion').attr('hidden', true);
    $("#registrarse").attr('hidden', true);
    $("#logearse").attr('hidden', true);

    $("#buscar").attr('hidden', true);

    checkLogin()
        .then((resultado) => {
            if(resultado) {
                $('#cerrarSesion').removeAttr('hidden');
                $("#buscar").removeAttr('hidden');
            } else {
                $("#registrarse").removeAttr('hidden');
                $("#logearse").removeAttr('hidden');
            }
        })
        .catch((error) => {
            console.log(error);
        }
    );
});