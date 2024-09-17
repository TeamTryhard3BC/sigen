$("#cerrarSesion").click((event) => {
    event.preventDefault();

    $.ajax({
        url: 'http://localhost/proyectosigen/negocio/modules/requests.php',
        method: 'GET',
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
                window.location = `http://localhost/proyectosigen/presentacion/html/${language}/login.html`;
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
});