import { BASE_PATH } from '../BASE_PATH.js';
import { idioma } from "./perfil.js";

$("#btnModificarUsuario").click(function () {
    let textosPerfil = {
        titulo: idioma == "spanish" ? "Formulario de modificación de usuario" : "User modification form",
        subtitulo: idioma == "spanish" ?
        "No todos los campos son necesarios a excepción del tipo de documento y el número de documento."
            :
        "Not all fields are required except for the document type and document number.",

        nombre: idioma == "spanish" ? "Nombre" : "Name",
        apellido: idioma == "spanish" ? "Apellido" : "Surname",
        tipoDocumento: idioma == "spanish" ? "Tipo de documento" : "Document type",
        documento: idioma == "spanish" ? "Documento" : "Document",
        fechaNacimiento: idioma == "spanish" ? "Fecha de nacimiento" : "Birth date",
        contrasena: idioma == "spanish" ? "Contraseña" : "Password",
        rol: idioma == "spanish" ? "Rol del usuario" : "User role",

        cedula: idioma == "spanish" ? "Cédula de identidad" : "Identity card",
        pasaporte: idioma == "spanish" ? "Pasaporte" : "Passport",

        cliente: idioma == "spanish" ? "Cliente" : "Client",
        entrenador: idioma == "spanish" ? "Entrenador" : "Trainer",
        administrador: idioma == "spanish" ? "Administrador" : "Administrator",

        enviar: idioma == "spanish" ? "Enviar" : "Send",
        placeholder: idioma == "spanish" ? "Escriba aqui..." : "Type here...",
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
                    <h6 class="modal-title" id="exampleModalLabel">${textosPerfil.subtitulo}</h6>
                    </br>
                    <form id="modificarForm">
                        <div class="mb-3 row">
                            <div class="col-6">
                                <label for="nombre" class="form-label">${textosPerfil.nombre}</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="${textosPerfil.placeholder}">
                            </div>

                            <div class="col-6">
                                <label for="apellido" class="form-label">${textosPerfil.apellido}</label>
                                <input type="text" id="apellido" name="apellido" class="form-control" placeholder="${textosPerfil.placeholder}">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-6">
                                <label for="tipoDocumento" class="form-label">${textosPerfil.tipoDocumento}</label>
                                <select class="form-select" id="tipoDocumento" name="tipoDocumento">
                                    <option selected value="ci">${textosPerfil.cedula}</option>
                                    <option value="pasaporte">${textosPerfil.pasaporte}</option>
                                </select>
                            </div>

                            <div class="col-6">
                                <label for="documento" class="form-label">${textosPerfil.documento}</label>
                                <input type="number" id="documento" name="documento" class="form-control" min="0" placeholder="1.234.567-8" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="fechaNacimiento" class="form-label">${textosPerfil.fechaNacimiento}</label>
                            <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" placeholder="">
                        </div>

                        <div class="mb-3">
                            <label for="contrasena" class="form-label">${textosPerfil.contrasena}</label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="${textosPerfil.placeholder}">
                        </div>

                        <div class="mb-3">
                            <label for="rol" class="form-label">${textosPerfil.rol}</label>
                            <select class="form-select" id="rol" name="rol">
                                <option selected value="Cliente">${textosPerfil.cliente}</option>
                                <option value="Entrenador">${textosPerfil.entrenador}</option>
                                <option value="Administrador">${textosPerfil.administrador}</option>
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

    $("#modificarForm").submit(function (event) {
        event.preventDefault();

        const nombreNuevo = $("#nombre").val();
        const apellidoNuevo = $("#apellido").val();
        const tipoDocumentoNuevo = $("#tipoDocumento").val();
        const documentoNuevo = $("#documento").val();
        const fechaNacimientoNuevo = $("#fechaNacimiento").val();
        const contrasenaNuevo = $("#contrasena").val();
        const rolNuevo = $("#rol").val();

        let url = BASE_PATH + '/negocio/logica/modificarUsuario.php'
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                nombre: nombreNuevo,
                apellido: apellidoNuevo,
                tipoDocumento: tipoDocumentoNuevo,
                documento: documentoNuevo,
                fechaNacimiento: fechaNacimientoNuevo,
                contrasena: contrasenaNuevo,
                rol: rolNuevo
            },
            success: function (response) {
                console.log(response)
                const retorno = JSON.parse(response);
                console.log(retorno);

                if (retorno.success) {
                    alert(retorno.success)
                    $("#myModal").modal('hide');

                    if(retorno.cerrarSesion) {
                        const language = $("html").attr("lang");
                        let url = BASE_PATH + `/presentacion/html/${language}/login.html`

                        window.location = url;
                    }
                } else {
                    alert(retorno.error)
                }
            },
            error: function (error) {
                alert(`Error al modificar el usuario: ${error}`);
            }
        });
    });
});