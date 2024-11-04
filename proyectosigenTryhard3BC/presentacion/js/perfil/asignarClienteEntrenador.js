import { BASE_PATH } from '../BASE_PATH.js';
import { obtenerUsuarios } from "../funcionesUtiles.js";
import { idioma } from "./perfil.js";

$("#btnAsignarClienteEntrenador").click(function () {
    let textosPerfil = {
        titulo: idioma == "spanish" ? "Asignar entrenador a cliente" : "Assign trainer to a client",

        cliente: idioma == "spanish" ? "Cliente a asignar" : "Client to assign to",
        multiple: idioma == "spanish" ? "Puedes seleccionar uno solo" : "You can only select one",
        enviar: idioma == "spanish" ? "Enviar" : "Send",
        paciente: idioma == "spanish" ? "Seleccione un paciente" : "Select a patient",


        seleccionCliente: idioma == "spanish" ? "Seleccione un deportista" : "Select an athlete",
        seleccionEntrenador: idioma == "spanish" ? "Seleccione un entrenador" : "Select a trainer",
    };

    const modalHTML = `
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">${textosPerfil.titulo}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="asignarEntrenadorForm">
                        <div class="mb-3">
                            <label for="usuarios" class="form-label">${textosPerfil.cliente}</label>
                            <select class="form-select select-auto-size" id="usuarios" name="usuarios" placeholder="">
                            </select>
                            <label for="usuarios"><em> (${textosPerfil.multiple})</em></label>
                        </div>
                        <div class="mb-3">
                            <label for="entrenadores" class="form-label">${textosPerfil.seleccionEntrenador}</label>
                            <select class="form-select select-auto-size" id="entrenadores" name="entrenadores" placeholder="">
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
            console.log(usuarios);

            $("#usuarios").html('');
            $("#usuarios").append(`
                <option disabled>${textosPerfil.seleccionCliente}</option>
            `);
            usuarios.Deportistas.forEach(deportista => {
                $("#usuarios").append(`
                    <option value="${deportista.id}">${deportista.id} - ${deportista.nombre} ${deportista.apellido}</option>
                `);
            });
            $("#usuarios").append(`
                <option disabled>${textosPerfil.paciente}</option>
            `);
            usuarios.Pacientes.forEach(paciente => {
                $("#usuarios").append(`
                    <option value="${paciente.id}">${paciente.id} - ${paciente.nombre} ${paciente.apellido}</option>
                `);
            });

            $("#entrenadores").html('');
            $("#entrenadores").append(`<option disabled selected>${textosPerfil.seleccionEntrenador}</option>`);
            usuarios.Entrenador.forEach(entrenador => {
                $("#entrenadores").append(`
                    <option value="${entrenador.id}">${entrenador.id} - ${entrenador.nombre} ${entrenador.apellido}</option>
                `);
            });
        })
        .catch((error) => {
            alert(`Error al obtener usuarios: ${error}`);
        });

        $("#asignarEntrenadorForm").submit(function (event) {
            event.preventDefault();
        
            const selectedUsuario = $("#usuarios").val();
            const selectedEntrenador = $("#entrenadores").val(); 
        
            let url = BASE_PATH + '/negocio/logica/entrenadores.php';
            $.ajax({
                url: url, 
                method: 'POST',
                data: {
                    codigoCliente: selectedUsuario,
                    codigoEntrenador: selectedEntrenador 
                },
                success: function (response) {
                    try {
                        const retorno = JSON.parse(response);
                        if (retorno.success) {
                            alert(retorno.success);
                            $("#myModal").modal('hide');
                        } else {
                            alert(retorno.error);
                        }
                    } catch (e) {
                        alert(`Error al analizar la respuesta JSON: ${e.message}`);
                    }
                },
                error: function (xhr, status, error) {
                    alert(`Error al enviar la información: ${xhr.responseText}`);
                }
            });
        });
        
});
