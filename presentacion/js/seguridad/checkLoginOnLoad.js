$(() => {
    $.ajax({
        url: 'http://localhost/proyectosigen/negocio/modules/requests.php',
        method: 'GET',
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
                window.location = `http://localhost/proyectosigen/presentacion/html/${language}/login.html`;
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
});