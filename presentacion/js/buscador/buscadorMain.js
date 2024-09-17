let datosEntidades = null;
let idioma = document.documentElement.lang; // preguntar al profe de dweb si esta bien tomado el idioma, es lo primero q sale en google
const maxNombre = 10; // maximo caracteres para el nombre pq sino se pasa y se ve feo
const maxDescripcion = 12; // maximo caracteres para la descripcion

const cargar = () => {
    $.ajax({
        url: 'http://localhost/proyectosigen/negocio/modules/requests.php',
        method: 'GET',
        data: {
            fileName: "requestBuscador",
        },
        success: function(data) {
            const retorno = JSON.parse(data);
            
            if (retorno) {
                datosEntidades = retorno;
                actualizar();
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
};

const actualizar = () => {
    if (!datosEntidades) { return }

    let textoBuscador = $("#searchInput").val().toLowerCase();

    $.each(datosEntidades, (nombreDato, valueDato) => {
        if(nombreDato == "combo") combo(textoBuscador, valueDato);
        if(nombreDato == "ejercicio") ejercicio(textoBuscador, valueDato);
    });
};

cargar();
//preguntar al profe si esto esta bien o hay una mejor alternativa para el "input"
//fue lo primero que me salio en google :v
$("#searchInput").on("input", actualizar);
