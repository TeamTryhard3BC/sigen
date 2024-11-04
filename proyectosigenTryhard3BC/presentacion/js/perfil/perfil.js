import { getDatosPersona, getConfiguracionGimnasio } from "../funcionesUtiles.js";

export let idioma = document.documentElement.lang;

$(document).ready(function () {
    let configuracionGimnasio;
    let datosPersona;

    getDatosPersona()
        .then((resultado) => {
            datosPersona = resultado
            console.log(datosPersona)

            $("#nombrePersona").html(`${datosPersona.nombre} ${datosPersona.apellido}`);
        })
        .catch((error) => {
            alert('Error al obtener configuracion de persona: ' + error)
        });

    getConfiguracionGimnasio()
        .then((resultado) => {
            configuracionGimnasio = resultado
            console.log(configuracionGimnasio)

            $("#nombregym").html(configuracionGimnasio.Nombre)

            $("#containerConSigen").html(`
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link active material-symbols-outlined icon" href="#"
                    data-toggle="tooltip" data-bs-placement="top" title="${configuracionGimnasio.Mail}">alternate_email</a>
                </li>

                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link active material-symbols-outlined icon" href="#"
                    data-toggle="tooltip" data-bs-placement="top" title="${configuracionGimnasio.Instagram}">perm_contact_calendar</a>
                </li>

                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link active material-symbols-outlined icon" href="#"
                    data-toggle="tooltip" data-bs-placement="top" title="${configuracionGimnasio.NumeroContacto}">call</a>
                </li>
            `);

            //esto es para la tooltip en los iconos del footer

            $('[data-toggle="tooltip"]').tooltip({
                trigger : 'hover'
            })

            //fix por si el texto del tooltip ya tiene un css aplicado
            $('[data-toggle="tooltip"]').on('inserted.bs.tooltip', function () {
                $('.tooltip-inner').css({
                    'color': '#fff',
                });
            });

            //logito de copyright con el nombre del gym
            $("#copyright").html(`
                © 2024, ${configuracionGimnasio.Nombre}
            `);
        })
        .catch((error) => {
            alert('Error al obtener configuracion del gimnasio: ' + error)
        });
});