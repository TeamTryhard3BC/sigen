import { obtenerDeportes, obtenerUsuarios } from "../funcionesUtiles.js";
import { BASE_PATH } from '../BASE_PATH.js';
import { idioma } from "./perfil.js";

$("#btnAsignarDeporte").click(function () {
    let textosPerfil = {
        titulo: idioma == "spanish" ? "Asignar deporte a deportista" : "Assign sport to an athlete",

        cliente: idioma == "spanish" ? "Cliente a asignar" : "Client to assign to",
        deporte: idioma == "spanish" ? "Deporte" : "Sport",

        multiple: idioma == "spanish" ? "Puedes seleccionar uno solo" : "You can only select one",
        enviar: idioma == "spanish" ? "Enviar" : "Send",

        seleccionDeportista: idioma == "spanish" ? "Seleccione un deportista" : "Select an athlete",
        seleccionDeporte: idioma == "spanish" ? "Seleccione un deporte" : "Select a sport",
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
                    <form id="asignarDeporteForm">
                        <div class="mb-3">
                            <label for="usuarios" class="form-label">${textosPerfil.cliente}</label>
                            <select class="form-select select-auto-size" id="usuarios" name="usuarios" placeholder="">
                            </select>
                            <label for="usuarios"><em> (${textosPerfil.multiple})</em></label>
                        </div>
                        <div class="mb-3">
                            <label for="deportes" class="form-label">${textosPerfil.deporte}</label>
                            <select class="form-select select-auto-size" id="deportes" name="deportes" placeholder="">
                            </select>
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
            $("#usuarios").html('');
            $("#usuarios").append(`<option disabled selected>${textosPerfil.seleccionDeportista}</option>`);
            usuarios.Deportistas.forEach(deportista => {
                $("#usuarios").append(`
                    <option value="${deportista.id}">${deportista.id} - ${deportista.nombre} ${deportista.apellido}</option>
                `);
            });
        })
        .catch((error) => {
            alert(`Error al obtener usuarios: ${error}`);
        });

    obtenerDeportes()
        .then((deportes) => {
            $("#deportes").html('');
            $("#deportes").append(`<option disabled selected>${textosPerfil.seleccionDeporte}</option>`);
            deportes.forEach(deporte => {
                $("#deportes").append(`<option value="${deporte.codigoDeporte}">${deporte.codigoDeporte} - ${deporte.nombreDeporte}</option>`);
            });
        })
        .catch((error) => {
            alert(`Error al obtener deportes: ${error}`);
        });

    $("#asignarDeporteForm").submit(function (event) {
        event.preventDefault();

        const selectedUsuario = $("#usuarios").val();
        const selectedDeporte = $("#deportes").val();

        let url = BASE_PATH + '/negocio/logica/deportes.php'
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                codigoPersona: selectedUsuario,
                codigoDeporte: selectedDeporte
            },
            success: function (response) {
                const retorno = JSON.parse(response);
                if (retorno.success) {
                    alert(retorno.success);
                    $("#myModal").modal('hide');
                } else {
                    alert(retorno.error);
                }
            },
            error: function (error) {
                alert(`Error al enviar la información: ${error}`);
            }
        });
    });
});
