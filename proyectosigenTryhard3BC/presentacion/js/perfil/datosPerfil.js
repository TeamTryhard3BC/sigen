import { getDatosPersona, getDatosUsuario, getCalificacionEstado, getCombosRealizados } from "../funcionesUtiles.js";
import { idioma } from "./perfil.js";

$(document).ready(function() {
    let datosPersona;
    let datosUsuario;

    let textosPerfil = {
        usuario: idioma == "spanish" ? "Usuario" : "Username",
        tipoDocumento: idioma == "spanish" ? "Tipo de documento" : "Document type",
        documento: idioma == "spanish" ? "Documento" : "Document",

        nombre: idioma == "spanish" ? "Nombre" : "Name",
        apellido: idioma == "spanish" ? "Apellido" : "Surname",
        fechaNacimiento: idioma == "spanish" ? "Fecha de nacimiento" : "Birth date",

        calificacionActual: idioma == "spanish" ? "Calificación actual" : "Current calification",
        calificacionFecha: idioma == "spanish" ? "Fecha de calificación" : "Calification date",
        estado: idioma == "spanish" ? "Estado" : "Status",
        imposibleCalificar: idioma == "spanish" ? "IMPOSIBLE CALIFICAR" : "COULD NOT CALIFICATE",

        entrenamiento: idioma == "spanish" ? "Entrenamiento" : "Exercise",
        queTrabaja: idioma == "spanish" ? "Este ejercicio trabaja los siguientes músculos" : "This exercise trains the following muscles"
    }

    //PERFIL-DATOS DE USUARIO
    getDatosUsuario()
        .then((resultado) => {
            datosUsuario = resultado
            console.log(datosUsuario)

            $("#usuarioPerfil").html(`${textosPerfil.usuario}: ${datosUsuario.tipoDocumento}-${datosUsuario.nroDocumento}`);
            $("#tipoDocumentoPerfil").html(`${textosPerfil.tipoDocumento}: ${datosUsuario.tipoDocumento}`);
            $("#nroDocumentoPerfil").html(`${textosPerfil.documento}: ${datosUsuario.nroDocumento}`);
        })
        .catch((error) => {
            alert('Error al obtener datos de usuario: ' + error)
        });

    getDatosPersona()
        .then((resultado) => {
            datosPersona = resultado
            console.log(datosPersona)

            $("#nombrePersonaPerfil").html(`${textosPerfil.nombre}: ${datosPersona.nombre}`);
            $("#apellidoPersonaPerfil").html(`${textosPerfil.apellido}: ${datosPersona.apellido}`);
            $("#fechaNacimientoPerfil").html(`${textosPerfil.fechaNacimiento}: ${datosPersona.fechaNacimiento}`);
        })
        .catch((error) => {
            alert('Error al obtener datos de persona: ' + error)
        });


    //PERFIL-ESTADO Y CALIFICACION
    getCalificacionEstado()
        .then((resultado) => {
            console.log(resultado);

            if(resultado.Calificacion && resultado.DatosEstado) {
                $("#calificacionUsuario").html(`${textosPerfil.calificacionActual}: ${resultado.Calificacion.puntaje} / 200`);
                $("#fechaCalificacionUsuario").html(`${textosPerfil.calificacionFecha}: ${resultado.Calificacion.fechaCalificacion}`);
                $("#estadoUsuario").html(`${textosPerfil.estado}: ${resultado.DatosEstado.Estado}`);
                $("#fechaEstadoUsuario").html(`${textosPerfil.calificacionFecha}: ${resultado.DatosEstado.fechaEstado}`);
            } else {
                $("#calificacionUsuario").html(`${textosPerfil.imposibleCalificar}`);
                $("#fechaCalificacionUsuario").html(``);
                $("#estadoUsuario").html(``);
                $("#fechaEstadoUsuario").html(``);
            }
        })
        .catch((error) => {
            alert('Error al obtener calificacion y estado ' + error)
        })


    //PERFIL-EJERCICIOS Y COMBOS ASIGNADOS
    getCombosRealizados()
        .then((resultado) => {
            if (!resultado.Combos) { return; }
            console.log(resultado);

            resultado.Combos.forEach(combo => {
                $("#containerCombos").append(`
                    <div class="col-6 text-center mx-auto my-5" id="${combo.codigoCombo}header">
                        <div class="mt-4">
                            <h4 class="text-center d-inline">${combo.nombreCombo}</h4>
    
                            <div class="mt-2">
                                <hr class="my-4 mt-3 lineaDivisoraSub">
    
                                <div class="mt-5">
                                    <div class="" id="${combo.codigoCombo}contenedor">
                                        <div class="accordion-body">
                `);

                combo.ejercicios.forEach((ejercicio, key) => {
                    $("#containerCombos").append(`
                                            <div class="accordion" id="entrenamiento${combo.codigoCombo}${ejercicio.codigoEjercicio}Accordion">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#entrenamiento${combo.codigoCombo}${ejercicio.codigoEjercicio}" aria-expanded="false">
                                                            ${textosPerfil.entrenamiento} ${key+1}: ${ejercicio.nombreEjercicio}
                                                        </button>
                                                    </h2>

                                                    <div id="entrenamiento${combo.codigoCombo}${ejercicio.codigoEjercicio}" class="accordion-collapse collapse" data-bs-parent="#entrenamiento${combo.codigoCombo}${ejercicio.codigoEjercicio}Accordion">
                                                        <div class="accordion-body">
                                                            <div class="card small-card">
                                                                <div class="card-body">
                                                                    <h3 class="card-title">${ejercicio.nombreEjercicio}</h3>
                                                                    <p class="card-text">${ejercicio.descripcion}<br><br>${textosPerfil.queTrabaja}: ${ejercicio.musculoTrabajado}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <br>
                    `);
                })

                $("#containerCombos").append(`
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);   
            });        
        })
        .catch((error) => {
            console.log(error);
            alert('Error al obtener combos ' + error)
        })
});
