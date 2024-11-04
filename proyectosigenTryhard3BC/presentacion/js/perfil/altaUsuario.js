import { obtenerUsuarios } from "../funcionesUtiles.js";
import { BASE_PATH } from '../BASE_PATH.js';
import { idioma } from "./perfil.js";

$("#btnAltaUsuario").click(function () {
    let textosPerfil = {
        titulo: idioma == "spanish" ? "Formulario de alta de usuario" : "User activation form",

        codigo: idioma == "spanish" ? "Código de persona del usuario a activar" : "User's person code to activate",
        seleccionCliente: idioma == "spanish" ? "Seleccione una persona" : "Select a person",
        multiple: idioma == "spanish" ? "Puedes seleccionar uno solo" : "You can only select one",
        paciente: idioma == "spanish" ? "Seleccione un paciente" : "Select a patient",

        seleccionDeportista: idioma == "spanish" ? "Seleccione un deportista" : "Select an athlete",

        ejemplo: idioma == "spanish" ? "Ejemplo:" : "Ex.",
        enviar: idioma == "spanish" ? "Enviar" : "Send",
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
                    <form id="altaForm">
                        <div class="mb-3">
                            <label for="codigoPersona" class="form-label">${textosPerfil.codigo}:</label>
                            <input type="number" id="codigoPersona" name="codigoPersona" class="form-control" min="0" placeholder="${textosPerfil.ejemplo} 123" required>
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

    $("#altaForm").submit(function (event) {
        event.preventDefault();

        const codigoPersona = $("#codigoPersona").val();

        if (!codigoPersona) {
            alert("Por favor, complete todos los campos.");
            return;
        }

        let url = BASE_PATH + '/negocio/logica/altaUsuario.php'
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                codigoPersona: codigoPersona,
            },
            success: function (response) {
                console.log(response);
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
                alert(`Error al darlo de alta el usuario: ${error}`);
            }
        });
    });
});