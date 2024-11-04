import { obtenerDeportes, obtenerCombos } from "../funcionesUtiles.js";
import { BASE_PATH } from '../BASE_PATH.js';
import { idioma } from "./perfil.js";

$("#btncomboDeporte").click(function () {
    let textosPerfil = {
        titulo: idioma == "spanish" ? "Asignar combo a deporte" : "Assign a combo to a sport",

        deporte: idioma == "spanish" ? "Deporte" : "Sport",

        multiple: idioma == "spanish" ? "Puedes seleccionar uno solo" : "You can only select one",
        enviar: idioma == "spanish" ? "Enviar" : "Send",

        seleccionDeporte: idioma == "spanish" ? "Seleccione un deporte" : "Select a sport",
        seleccionCombo: idioma == "spanish" ? "Seleccione un combo" : "Select a combo",
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
                    <form id="asignarcomboaDeporteForm">
                        <div class="mb-3">
                            <label for="combos" class="form-label">Combo</label>
                            <select class="form-select select-auto-size" id="combos" name="combos" placeholder="">
                            </select>
                            <label for="usuarios"><em> (${textosPerfil.multiple})</em></label>
                        </div>
                        <div class="mb-3">
                            <label for="deportes" class="form-label">${textosPerfil.deporte}</label>
                            <select class="form-select select-auto-size" id="deportes" name="deportes" placeholder="">
                            </select>
                            <label for="usuarios"><em> (${textosPerfil.multiple})</em></label>
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


        obtenerCombos() 
        .then((combos) => {
            $("#combos").html('');
            $("#combos").append(`<option disabled selected>${textosPerfil.seleccionCombo}</option>`);
            combos.forEach(combo => {
                $("#combos").append(`<option value="${combo.codigoCombo}">${combo.codigoCombo} - ${combo.nombreCombo}</option>`);
            });
        })
        .catch((error) => {
            alert(`Error al obtener combos: ${error}`);
        });

    $("#asignarcomboaDeporteForm").submit(function (event) {
        event.preventDefault();

        const selectedDeporte = $("#deportes").val();
        const selectedCombo = $("#combos").val();

        let url = BASE_PATH + '/negocio/logica/comboPerteneceDeporte.php'
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                codigoDeporte: selectedDeporte,
                codigoCombo: selectedCombo
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
