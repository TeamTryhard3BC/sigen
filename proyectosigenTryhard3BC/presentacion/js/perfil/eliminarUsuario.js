import { obtenerUsuarios } from "../funcionesUtiles.js";
import { BASE_PATH } from '../BASE_PATH.js';
import { idioma } from "./perfil.js";

$("#btnEliminarUsuario").click(function () {
    let textosPerfil = {
        titulo: idioma == "spanish" ? "Formulario de baja de usuario" : "User deactivation form",

        codigo: idioma == "spanish" ? "Código de persona del usuario a desactivar" : "User's person code to deactivate",

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
                    <form id="eliminacionForm">
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

    $("#eliminacionForm").submit(function (event) {
        event.preventDefault();

        const codigoPersona = $("#codigoPersona").val();

        if (!codigoPersona) {
            alert("Por favor, complete todos los campos.");
            return;
        }

        let url = BASE_PATH + '/negocio/logica/eliminarUsuario.php'
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
                alert(`Error al eliminar el usuario: ${error}`);
            }
        });
    });
});