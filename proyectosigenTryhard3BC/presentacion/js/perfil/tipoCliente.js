import { obtenerUsuarios } from "../funcionesUtiles.js";
import { BASE_PATH } from '../BASE_PATH.js';
import { idioma } from "./perfil.js";

$("#btntipoCliente").click(function () {
    let textosPerfil = {
        titulo: idioma == "spanish" ? "Definir tipo de cliente" : "Set client type",

        cliente: idioma == "spanish" ? "Cliente a asignar" : "Client to assign to",
        type: idioma == "spanish" ? "Tipo de cliente" : "Client type",

        paciente: idioma == "spanish" ? "Paciente" : "Patient",
        deportista: idioma == "spanish" ? "Deportista" : "Athlete",

        multiple: idioma == "spanish" ? "Puedes seleccionar uno solo" : "You can only select one",

        ejemplo: idioma == "spanish" ? "Ejemplo:" : "Ex.",
        enviar: idioma == "spanish" ? "Enviar" : "Send",

        seleccionCliente: idioma == "spanish" ? "Seleccione un cliente" : "Select a client",
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
                    <form id="tipoClienteForm">
                        <div class="mb-3">
                            <label for="usuarios" class="form-label">${textosPerfil.cliente}</label>
                            <select class="form-select select-auto-size" id="usuarios" name="usuarios" placeholder="${textosPerfil.ejemplo} 123">
                            </select>
                            <label for="usuarios"><em> (${textosPerfil.multiple})</em></label>
                        </div>
                        <div class="mb-3">
                            <label for="tipoUsuario" class="form-label">${textosPerfil.type}</label>
                            <select class="form-select select-auto-size" id="tipoUsuario" name="tipoUsuario">
                                <option value="deportista">${textosPerfil.deportista}</option>
                                <option value="paciente">${textosPerfil.paciente}</option>
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
            $("#usuarios").append(`<option disabled selected>${textosPerfil.seleccionCliente}</option>`);
            usuarios.Cliente.forEach(cliente => {
                $("#usuarios").append(`<option value="${cliente.id}">${cliente.id} - ${cliente.nombre} ${cliente.apellido}</option>`);
            });
        })
        .catch((error) => {
            alert(`Error al obtener usuarios: ${error}`);
        });

    $("#tipoClienteForm").submit(function (event) {
        event.preventDefault();

        const selectedUsuario = $("#usuarios").val();
        const tipoUsuario = $("#tipoUsuario").val();

        let url = BASE_PATH + '/negocio/logica/tipoCliente.php'
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                codigoPersona: selectedUsuario,
                tipoUsuario: tipoUsuario
            },
            success: function (response) {
                const retorno = JSON.parse(response);
                console.log(retorno);
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