import { checkLogin } from "./checkLogin.js";
import { getDatosUsuario } from "../funcionesUtiles.js";
import { BASE_PATH } from '../BASE_PATH.js';

$(() => {
    const language = $("html").attr("lang");

    $('#cerrarSesion').attr('hidden', true);
    $("#registrarse").attr('hidden', true);
    $("#logearse").attr('hidden', true);

    $("#buscar").attr('hidden', true);

    $("#perfilUsuario").attr('hidden', true);

    checkLogin()
        .then((resultado) => {
            if(resultado) {
                $('#cerrarSesion').removeAttr('hidden');
                $("#buscar").removeAttr('hidden');
                $("#perfilUsuario").removeAttr('hidden');

                getDatosUsuario()
                    .then((resultado) => {
                        console.log(resultado);

                        let tieneRolCliente = (resultado.rol === "Cliente")
                        console.log(tieneRolCliente)
                        !tieneRolCliente && (() => {
                            $('#btnGestorUsuarios').removeAttr('hidden');
                        })();
                    })
                    .catch((error) => {
                        alert('Error al obtener datos de usuario: ' + error)
                    });
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