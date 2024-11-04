import { getDatosUsuario } from "../funcionesUtiles.js";
import { combo, crearCombo } from "./combo.js";
import { ejercicio, crearEjercicio } from "./ejercicio.js";
import { grupomuscular, crearGrupoMuscular, editarGrupoMuscular } from "./grupomuscular.js";
import { BASE_PATH } from '../BASE_PATH.js';

let datosEntidades = null;
export let idioma = document.documentElement.lang; // preguntar al profe de dweb si esta bien tomado el idioma, es lo primero q sale en google
export const maxNombre = 10; // maximo caracteres para el nombre pq sino se pasa y se ve feo
export const maxDescripcion = 12; // maximo caracteres para la descripcion

export let tieneRolCliente = null;

$(() => {
    getDatosUsuario()
        .then((resultado) => {
            console.log(resultado);

            tieneRolCliente = (resultado.rol === "Cliente")
            console.log(tieneRolCliente)
            !tieneRolCliente && (() => {
                $('#grupomuscularNuevo').removeAttr('hidden');
                $('#ejercicioNuevo').removeAttr('hidden');
                $('#comboNuevo').removeAttr('hidden');
            })();

            !tieneRolCliente && $(document).on("click", ".botonCrear", function() {
                crearNuevo($(this).attr("id"));
            })

            cargar();
        })
        .catch((error) => {
            alert('Error al obtener datos de usuario: ' + error)
        });
});

export const cargar = () => {
    let url = BASE_PATH + '/negocio/modules/requests.php'

    $.ajax({
        url: url,
        method: 'POST',
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

export const actualizar = () => {
    if (!datosEntidades) { return }

    let textoBuscador = $("#searchInput").val().toLowerCase();

    $.each(datosEntidades, (nombreDato, valueDato) => {
        if(nombreDato == "combo") combo(textoBuscador, valueDato);
        if(nombreDato == "ejercicio") ejercicio(textoBuscador, valueDato);
        if(nombreDato == "grupomuscular") grupomuscular(textoBuscador, valueDato);
    });
};

const crearNuevo = (id) => {
    console.log(id);

    if(id.includes("combo")) {
        crearCombo()
        return;
    }

    if(id.includes("ejercicio")) {
        crearEjercicio()
        return;
    }

    if(id.includes("grupomuscular")) {
        crearGrupoMuscular()
        return;
    }
}

//preguntar al profe si esto esta bien o hay una mejor alternativa para el "input"
//fue lo primero que me salio en google :v
$("#searchInput").on("input", actualizar);