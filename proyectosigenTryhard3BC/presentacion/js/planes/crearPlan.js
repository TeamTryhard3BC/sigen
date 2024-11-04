import { actualizar, idioma } from "./planes.js";
import { BASE_PATH } from '../BASE_PATH.js';

export const crearPlan = () => {
    let textosPlanes = {
        titulo: idioma == "spanish" ? "Formulario de creación de plan" : "Plan creation form",

        nombre: idioma == "spanish" ? "Nombre" : "Name",

        duration: idioma == "spanish" ? "Duración" : "Duration",
        durationMes: idioma == "spanish" ? "mes" : "month",
        durationMeses: idioma == "spanish" ? "meses" : "months",

        precio: idioma == "spanish" ? "Precio" : "Price",
        descripcion: idioma == "spanish" ? "Descripción" : "Description",

        escribaAqui: idioma == "spanish" ? "Escriba aqui..." : "Type here...",
        enviar: idioma == "spanish" ? "Enviar" : "Send",
    }

    const modalHTML = `
    <div class="modal fade" id="modalCrearPlan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">${textosPlanes.titulo}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="creacionForm">
                        <div class="mb-3 row">
                            <div class="col-6">
                                <label for="nombre" class="form-label">${textosPlanes.nombre}</label>
                                <input type="text" id="crearNombre" name="nombre" class="form-control" placeholder="${textosPlanes.escribaAqui}" required>
                            </div>

                            <div class="col-6">
                                <label for="duracion" class="form-label">${textosPlanes.duration}</label>
                                <select class="form-select" id="crearDuracion" name="duracion">
                                    <option selected value="1">1 ${textosPlanes.durationMes}</option>
                                    <option value="2">2 ${textosPlanes.durationMeses}</option>
                                    <option value="3">3 ${textosPlanes.durationMeses}</option>
                                    <option value="4">4 ${textosPlanes.durationMeses}</option>
                                    <option value="5">5 ${textosPlanes.durationMeses}</option>
                                    <option value="6">6 ${textosPlanes.durationMeses}</option>
                                    <option value="7">7 ${textosPlanes.durationMeses}</option>
                                    <option value="8">8 ${textosPlanes.durationMeses}</option>
                                    <option value="9">9 ${textosPlanes.durationMeses}</option>
                                    <option value="10">10 ${textosPlanes.durationMeses}</option>
                                    <option value="11">11 ${textosPlanes.durationMeses}</option>
                                    <option value="12">12 ${textosPlanes.durationMeses}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label">${textosPlanes.precio}</label>
                            <input type="number" id="crearPrecio" name="precio" class="form-control" min="0" placeholder="14.99" required>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">${textosPlanes.descripcion}</label>
                            <textarea type="text" class="form-control col-form-label" id="crearDescripcion" name="descripcion" placeholder="${textosPlanes.escribaAqui}" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">${textosPlanes.enviar}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    `;

    $("#modalContainer").html(modalHTML);
    $("#modalCrearPlan").modal('show');

    $("#creacionForm").submit(function (event) {
        event.preventDefault();

        const nombreCreacion = $("#crearNombre").val();
        const duracionCreacion = $("#crearDuracion").val();
        const precioCreacion = $("#crearPrecio").val();
        const descripcionCreacion = $("#crearDescripcion").val();

        let url = BASE_PATH + '/negocio/modules/requests.php'
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                fileName: "crearPlan",
                nombre: nombreCreacion,
                duracion: duracionCreacion,
                precio: precioCreacion,
                descripcion: descripcionCreacion
            },
            success: function (response) {
                console.log(response);
                const retorno = JSON.parse(response);
                console.log(retorno);

                if (retorno.success) {
                    alert(retorno.success)
                    $("#modalCrearPlan").modal('hide');
                                    
                    //esto para desbindear el evento click para no stackear infinitas funciones una arriba de otra y capaz q lagear
                    $('#modalCrearPlan').on('hidden.bs.modal', function () {
                        $('#modalCrearPlan').remove();
                        $("#botonAgregarPlan").off("click");

                        actualizar();
                    });
                } else {
                    alert(retorno.error)
                }
            },
            error: function (error) {
                alert(`Error al crear el plan: ${error}`);
            }
        });
    });
}

