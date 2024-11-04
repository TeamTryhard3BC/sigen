import { BASE_PATH } from '../BASE_PATH.js';
import { idioma } from "./perfil.js";

$("#btnCrearDeporte").click(function () {
    let textosPerfil = {
        titulo: idioma == "spanish" ? "Crear nuevo deporte" : "Create a new sport",

        nombre: idioma == "spanish" ? "Nombre" : "Name",
        descripcion: idioma == "spanish" ? "Descripción" : "Description",
        reglas: idioma == "spanish" ? "Reglas" : "Ruleset",

        crear: idioma == "spanish" ? "Crear deporte" : "Create sport",

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
                    <form id="crearDeporteForm">
                        <div class="mb-3">
                            <label for="nombreDeporte" class="form-label">${textosPerfil.nombre}</label>
                            <input type="text" class="form-control" id="nombreDeporte" name="nombreDeporte" placeholder="${textosPerfil.placeholder}" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcionDeporte" class="form-label">${textosPerfil.descripcion}</label>
                            <textarea class="form-control" id="descripcionDeporte" name="descripcionDeporte" placeholder="${textosPerfil.placeholder}" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="reglasDeporte" class="form-label">${textosPerfil.reglas}</label>
                            <textarea class="form-control" id="reglasDeporte" name="reglasDeporte" placeholder="${textosPerfil.placeholder}" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">${textosPerfil.crear}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    `;
    $("#modalContainer").html(modalHTML);
    $("#myModal").modal('show');

    $("#crearDeporteForm").submit(function (event) {
        event.preventDefault();

        const nombreDeporte = $("#nombreDeporte").val();
        const descripcionDeporte = $("#descripcionDeporte").val();
        const reglasDeporte = $("#reglasDeporte").val();

        let url = BASE_PATH + '/negocio/logica/crearDeporte.php'
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                nombreDeporte: nombreDeporte,
                descripcionDeporte: descripcionDeporte,
                reglasDeporte: reglasDeporte
            },
            success: function (response) {
                console.log(response);
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
