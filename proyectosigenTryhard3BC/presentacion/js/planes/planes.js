import { getConfiguracionGimnasio } from "../funcionesUtiles.js";
import { crearPlan } from "./crearPlan.js";
import { modificarPlan } from "./modificarPlan.js";
import { getDatosUsuario } from "../funcionesUtiles.js";
import { BASE_PATH } from '../BASE_PATH.js';

export let cantPlanes;
export let idioma = document.documentElement.lang;
export let tieneRolCliente = null;
export let tieneRolAdmin = null;

$(() => {
    getDatosUsuario()
        .then((resultado) => {
            console.log(resultado);

            tieneRolCliente = resultado && resultado.rol === "Cliente";
            tieneRolAdmin = resultado && resultado.rol === "Administrador";

            actualizar()
        })
        .catch((error) => {
            alert('Error al obtener datos de usuario: ' + error)
        });
});

const mostrarModal = () => {    
    if ($("#myModal").length === 0) {
        let textosPlanes = {
            titulo: idioma == "spanish" ? "Formulario de Pagos" : "Payment form",

            mop: idioma == "spanish" ? "Método de pago" : "Payment method",
            debito: idioma == "spanish" ? "Débito" : "Debit",
            credito: idioma == "spanish" ? "Crédito" : "Credit",
            efectivo: idioma == "spanish" ? "Efectivo" : "Cash",

            multiple: idioma == "spanish" ? "Puedes seleccionar uno solo" : "You can only select one",
            cuotas: idioma == "spanish" ? "Cuotas" : "Payment installments",

            send: idioma == "spanish" ? "Enviar" : "Send",
        }

        const modalHTML = `
            <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">${textosPlanes.titulo}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="pagoForm">
                                <div class="mb-3">
                                    <label for="usuarios" class="form-label">${textosPlanes.mop}</label>
                                    <select class="form-select" id="metodoPago" name="metodoPago" required>
                                        <option value="debito">${textosPlanes.debito}</option>
                                        <option value="credito">${textosPlanes.credito}</option>
                                        <option value="efectivo">${textosPlanes.efectivo}</option>
                                    </select>
                                    <label for="pagos"><em> (${textosPlanes.multiple})</em></label>
                                </div>
                                <div class="mb-3">
                                    <label for="cuotas" class="form-label">${textosPlanes.cuotas}:</label>
                                    <input type="number" id="cuotas" name="cuotas" class="form-control" min="1" required>
                                </div>
                                <button type="submit" class="btn btn-primary">${textosPlanes.send}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $("body").append(modalHTML); 
    }

    $("#myModal").modal("show");

    let codigoPersona;

    getDatosUsuario()
        .then((resultado) => {
            console.log(resultado);
            codigoPersona = resultado.codigoPersona; 
        })
        .catch((error) => {
            alert('Error al obtener datos de usuario: ', error);
        });

    $("#pagoForm").on("submit", function(event) {
        event.preventDefault(); 

        const metodoPago = $("#metodoPago").val();
        const cuotas = $("#cuotas").val();

        let url = BASE_PATH + '/negocio/logica/pagos.php'
        $.ajax({
            type: "POST",
            url: url, 
            data: { metodoPago: metodoPago, cuotas: cuotas, codigoPersona: codigoPersona },
            success: function(response) {
                alert(response); 
                $("#myModal").modal("hide"); 
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud:", error);
                alert("Error al procesar el pago. Intenta nuevamente.");
            }
        });
    });
};

export const actualizar = () => {
    let configuracionGimnasio;
    let btnHideSubscribir = tieneRolCliente ? null : "hidden";
    let btnHideEditar = tieneRolAdmin ? null : "hidden";

    console.log(tieneRolAdmin, tieneRolCliente)
    console.log(btnHideEditar, btnHideSubscribir)

    getConfiguracionGimnasio()
        .then((resultado) => {
            configuracionGimnasio = resultado;
            console.log(configuracionGimnasio);

            $("#nombregym").html(configuracionGimnasio.Nombre);
            $(`#containerPlanes`).html("");

            cantPlanes = configuracionGimnasio.planes ? configuracionGimnasio.planes.length : 0;

            let textosPlanes = {
                durationMes: idioma == "spanish" ? "mes" : "month",
                durationMeses: idioma == "spanish" ? "meses" : "months",

                subscribirse: idioma == "spanish" ? "Subscribirse" : "Subscribe",
                editar: idioma == "spanish" ? "Editar" : "Edit",
            }

            cantPlanes > 0 && configuracionGimnasio.planes.forEach((plan, index) => {
                let duracion = plan.Duracion > 1 ? `${plan.Duracion} ${textosPlanes.durationMeses}` : `${plan.Duracion} ${textosPlanes.durationMes}`;
                let descripcionFull = plan.Descripcion;
                let descripcionAcortada = descripcionFull.substr(0, 325);

                if (descripcionFull.length > 325) {
                    descripcionAcortada += "...";
                }

                $(`#containerPlanes`).append(`
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">${plan.Nombre}</h3>
                                <h5>${duracion}</h5>
                                <p class="card-text">${descripcionAcortada}</p>
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">UYU ${plan.Precio}</h3>
                            </div>
                        </div>
                        <br>
                        <button type="button" id="btnSubscribir${index}" class="col-lg-5 mb-2 btn btn-outline-secondary" style="color: rgb(255, 255, 255); border-color: rgb(255, 255, 255);" ${btnHideSubscribir}>${textosPlanes.subscribirse}</button>
                        <button type="button" id="btnEditar${index}" class="col-lg-5 mb-2 btn" style="color: rgb(13, 110, 253); border-color: transparent;" ${btnHideEditar}>${textosPlanes.editar}</button>
                    </div>
                `);

                $(`#btnSubscribir${index}`).click(mostrarModal);
                tieneRolAdmin && $(`#btnEditar${index}`).click(() => modificarPlan(plan, index));
            });

            if (cantPlanes < 3 && tieneRolAdmin) {
                $(`#containerPlanes`).append(`
                    <div class="col-md-6 col-lg-3 mb-5">
                        <div class="card" id="cardAgregarPlan">
                            <button type="button" id="botonAgregarPlan" class="btn btn-outline-secondary">+</button>
                        </div>
                    </div>
                `);
                
                $("#botonAgregarPlan").click(() => crearPlan());
            }
        })
        .catch((error) => {
            alert('Error al obtener configuracion del gimnasio: ' + error);
        });

    console.log(configuracionGimnasio);
};

