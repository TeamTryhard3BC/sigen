import { obtenerSugerenciasDeportesPorCombos } from "../funcionesUtiles.js";
import { idioma } from "./perfil.js";

$("#btnListarSugerencias").click(function () {
    let textosPerfil = {
        titulo: idioma == "spanish" ? "Deportes recomendados para combos" : "Recommended sports for combos",

        no: idioma == "spanish" ? "No hay deportes recomendados" : "There's no recommended sports",
        paraDeporte: idioma == "spanish" ? "Para el deporte" : "For the sport",
        seRecomienda: idioma == "spanish" ? "se recomienda el siguiente combo" : "the following combo is recommended",
    }

    const modalHTML = `
    <div class="modal fade" id="sugerenciasModal" tabindex="-1" aria-labelledby="sugerenciasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sugerenciasModalLabel">${textosPerfil.titulo}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="listaSugerencias" class="list-group">
                        <!-- Las sugerencias se llenarán aquí -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
    `;
    $("#modalContainer").html(modalHTML);
    $("#sugerenciasModal").modal('show');

    obtenerSugerenciasDeportesPorCombos()
        .then((sugerencias) => {
            console.log(sugerencias);
            $("#listaSugerencias").html('');
            if (sugerencias.length === 0) {
                $("#listaSugerencias").append(`<li class="list-group-item">${textosPerfil.no}.</li>`);
            } else {
                sugerencias.forEach(deporte => {
                    $("#listaSugerencias").append(`
                        <li class="list-group-item">${textosPerfil.paraDeporte} ${deporte.nombreDeporte}, ${textosPerfil.seRecomienda}: ${deporte.nombreCombo}</li>
                    `);
                });
            }
        })
        .catch((error) => {
            alert(`Error al obtener sugerencias de deportes: ${error}`);
        });
});

