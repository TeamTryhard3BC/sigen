import { obtenerUsuarios } from "../funcionesUtiles.js";
import { BASE_PATH } from '../BASE_PATH.js';
import { idioma } from "./perfil.js";

$("#btnCalificarCliente").click(function () {
    let textosPerfil = {
        titulo: idioma == "spanish" ? "Formulario de calificación" : "Calification form",

        cliente: idioma == "spanish" ? "Cliente a calificar" : "Client to calificate",
        calificacion: idioma == "spanish" ? "Calificación" : "Calification",

        multiple: idioma == "spanish" ? "Puedes seleccionar uno solo" : "You can only select one",
        enviar: idioma == "spanish" ? "Enviar" : "Send",

        deportistas: idioma == "spanish" ? "Deportistas" : "Select an athlete",
        pacientes: idioma == "spanish" ? "Pacientes" : "Select a patient",

        ejemplo: idioma == "spanish" ? "Ejemplo:" : "Ex.",
    }

    const modalHTML = `
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">${textosPerfil.titulo}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="calificacionForm">
                        <div class="mb-3">
                            <label for="usuarios" class="form-label">${textosPerfil.cliente}</label>
                            <select class="form-select select-auto-size" id="usuarios" name="usuarios[]" placeholder="">
                            </select>
                            <label for="usuarios"><em> (${textosPerfil.multiple})</em></label>
                        </div>
                        <div class="mb-3">
                            <label for="calificacion" class="form-label">${textosPerfil.calificacion}:</label>
                            <input type="number" id="calificacion" name="calificacion" class="form-control" min="80" max="200" placeholder="${textosPerfil.ejemplo} 80" required>
                        </div>
                        <button type="submit" class="btn btn-primary">${textosPerfil.enviar}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    `;

    $("#modalContainer").html(modalHTML);
    $("#myModal").modal('show');

    obtenerUsuarios()
        .then((usuarios) => {
            console.log(usuarios);

            $("#usuarios").html('');
            $("#usuarios").append(`
                <option disabled>${textosPerfil.deportistas}</option>
            `);
            usuarios.Deportistas.forEach(deportista => {
                $("#usuarios").append(`
                    <option value="${deportista.id}">${deportista.id} - ${deportista.nombre} ${deportista.apellido}</option>
                `);
            });
            $("#usuarios").append(`
                <option disabled>${textosPerfil.pacientes}</option>
            `);
            usuarios.Pacientes.forEach(paciente => {
                $("#usuarios").append(`
                    <option value="${paciente.id}">${paciente.id} - ${paciente.nombre} ${paciente.apellido}</option>
                `);
            });
        })
        .catch((error) => {
            alert(`Error al obtener usuarios: ${error}`);
        });

    $("#calificacionForm").submit(function (event) {
        event.preventDefault();

        const selectedUsuario = $("#usuarios").val();
        const calificacion = $("#calificacion").val();

        let url = BASE_PATH + '/negocio/logica/guardarCalificacion.php'
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                codigoPersona: selectedUsuario,
                puntajeCliente: calificacion
            },
            success: function (response) {
                const retorno = JSON.parse(response);
                console.log(retorno);

                if (retorno.success) {
                    alert(retorno.success)
                    $("#myModal").modal('hide');
                } else {
                    alert(retorno.error)
                }
            },
            error: function (error) {
                alert(`Error al enviar la calificación: ${error}`);
            }
        });
    });
});