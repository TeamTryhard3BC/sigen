import { BASE_PATH } from '../BASE_PATH.js';
import { obtenerCombos, obtenerUsuarios } from "../funcionesUtiles.js";
import { idioma } from "./perfil.js";

$("#btnAsignarCombo").click(function () {
    let textosPerfil = {
        titulo: idioma == "spanish" ? "Asignar combo a cliente" : "Assign combo to a client",

        cliente: idioma == "spanish" ? "Cliente a asignar" : "Client to assign to",
        multiple: idioma == "spanish" ? "Puedes seleccionar uno solo" : "You can only select one",
        enviar: idioma == "spanish" ? "Enviar" : "Send",
        paciente: idioma == "spanish" ? "Seleccione un paciente" : "Select a patient",

        seleccionDeportista: idioma == "spanish" ? "Seleccione un deportista" : "Select an athlete",
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
                    <form id="asignarComboForm">
                        <div class="mb-3">
                            <label for="usuarios" class="form-label">${textosPerfil.cliente}</label>
                            <select class="form-select select-auto-size" id="usuarios" name="usuarios" placeholder="">
                            </select>
                            <label for="usuarios"><em> (${textosPerfil.multiple})</em></label>
                        </div>
                        <div class="mb-3">
                            <label for="combos" class="form-label">Combo</label>
                            <select class="form-select select-auto-size" id="combos" name="combos" placeholder="">
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
            <option disabled>${textosPerfil.seleccionDeportista}</option>
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
    })
        .catch((error) => {
            alert(`Error al obtener usuarios: ${error}`);
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

    $("#asignarComboForm").submit(function (event) {
        event.preventDefault();

        const selectedUsuario = $("#usuarios").val();
        const selectedCombo = $("#combos").val(); 
        
        let url = BASE_PATH + '/negocio/logica/combos.php'
        $.ajax({
            url: url, 
            method: 'POST',
            data: {
                codigoPersona: selectedUsuario,
                codigoCombo: selectedCombo 
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
