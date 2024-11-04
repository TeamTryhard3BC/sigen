import { getDatosUsuario } from "../funcionesUtiles.js";
import { BASE_PATH } from '../BASE_PATH.js';
import { idioma } from "./perfil.js";

$(() => {
    getDatosUsuario()
        .then((resultado) => {
            console.log(resultado);

            let rol = resultado.rol
            let tieneRolCliente = (rol === "Cliente")
            console.log(tieneRolCliente)

            const language = $("html").attr("lang");
            const respuesta = language == "spanish" && "¡No tienes acceso a esta página!" || "You do not have access to this page!";

            (() => {                
                if(tieneRolCliente) {
                    alert(respuesta);
                    let url = BASE_PATH + `/presentacion/html/${language}/index.html`
                    window.location = url;
                    return;
                }

                if(rol === "Administrador") {
                    $('#Administradores').removeAttr('hidden')
                    $('#Entrenadores').removeAttr('hidden') 
                    $('#lineaDiv').removeAttr('hidden');

                    return
                } else {
                    $('#Entrenadores').removeAttr('hidden') 
                    $('#lineaDiv').removeAttr('hidden');

                    $("#btnCrearUsuario").off("click");
                    $("#btnModificarUsuario").off("click");
                    $("#btnEliminarUsuario").off("click");

                    return
                }
            })();
        })
        .catch((error) => {
            alert('Error al obtener datos de usuario: ' + error)
        });
});