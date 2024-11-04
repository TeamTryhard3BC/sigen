import { solicitarDatosTabla, crearEnTabla, modificarEnTabla, eliminarEnTabla } from "../funcionesUtiles.js";
import { tieneRolCliente, idioma, maxNombre, maxDescripcion, cargar, actualizar } from "./buscadorMain.js";

let ejerciciosCreados = []

export const ejercicio = (textoBuscador, tabla) => {
    let found = false;
    let btnHide = tieneRolCliente && "hidden" || null

    $.each(tabla, (key, value) => {
        const codigoEjercicio = value.codigoEjercicio;
        const nombreFull = value.nombreEjercicio;
        const descripcionFull = value.descripcion;
        const musculoTrabajado = value.musculoTrabajado;
        value.encontrado = false;

        let nombreAcortado = nombreFull.substr(0, maxNombre);
        let descripcionAcortada = descripcionFull.substr(0, maxDescripcion);

        if (nombreFull.length > maxNombre) {
            nombreAcortado = nombreAcortado + "...";
        }
        
        if (descripcionFull.length > maxDescripcion) {
            descripcionAcortada = descripcionAcortada + "...";
        }

        const nombreMinusculas = nombreFull.toLowerCase();
        const descripcionMinusculas = descripcionFull.toLowerCase();

        //console.log(textoBuscador);

        if (textoBuscador == "" || nombreMinusculas.indexOf(textoBuscador) !== -1 || descripcionMinusculas.indexOf(textoBuscador) !== -1) {
            $(`#ejercicioHeader`).show();
            $(`#${codigoEjercicio}Ejercicio`).show();
            value.encontrado = true;

            let verMas = idioma == "spanish" && "Ver más" || "View more"
            let editar = idioma == "spanish" && "Editar" || "Edit"
            let editando = idioma == "spanish" && "Editando" || "Editing"
            let cerrar = idioma == "spanish" && "Cerrar" || "Close"
            let guardar = idioma == "spanish" && "Guardar cambios" || "Save changes"
            let confirmar = idioma == "spanish" && "Confirmar cambios" || "Confirm changes"
            let eliminar = idioma == "spanish" && "Eliminar" || "Delete"
            let cancelar = idioma == "spanish" && "Cancelar" || "Cancel"

            let textosEjercicio = {
                queEntrena: idioma == "spanish" ? "Este ejercicio entrena los siguientes músculos" : "This exercise trains the following muscles",

                nuevoNombre: idioma == "spanish" ? "Nuevo nombre ejercicio" : "New exercise name",
                nuevoNombreP: idioma == "spanish" ? "Ingrese el nuevo nombre del ejercicio" : "Enter a new exercise name",

                nuevaDescripcion: idioma == "spanish" ? "Nueva descripción" : "New description",
                nuevaDescripcionP: idioma == "spanish" ? "Ingrese la nueva descripción del ejercicio" : "Enter a new exercise description",

                nuevoMusculo: idioma == "spanish" ? "Nuevo músculo trabajado" : "New trained muscle",
                musculo: idioma == "spanish" ? "Seleccione un músculo" : "Select a muscle",
            }

            //console.log(nombreFull);

            //<img src="../../media/150.png" class="card-img" alt="Image Description">

            if(!ejerciciosCreados.includes(codigoEjercicio)) {
                $(`#ejercicioContenedor`).append(`
                    <div class="col" id="${codigoEjercicio}Ejercicio">
                        <div class="card card-horizontal">
                                <div class="card-body">
                                    <h5 class="card-title">${nombreAcortado}</h5>
                                    <p class="card-text">${descripcionAcortada}</p>
                                    <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#${codigoEjercicio}EjercicioView">${verMas}</a>
                                </div>
                            <div class="card-footer text-muted">
                                <a href="javascript:;" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#${codigoEjercicio}EjercicioEdit" ${btnHide}>${editar}</a>
                            </div>
                        </div>
                    </div>
                `);
    
                $(`#modalContainer`).append(`
                    <div class="modal fade" id="${codigoEjercicio}EjercicioView" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-dark" id="exampleModalLabel">${nombreFull}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="${cerrar}"></button>
                                </div>
                                <div class="modal-body text-dark">
                                    ${descripcionFull}

                                    </br> </br> </br>
                                    ${textosEjercicio.queEntrena}: ${musculoTrabajado}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${cerrar}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal fade" id="${codigoEjercicio}EjercicioEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-dark" id="exampleModalLabel">${editando} ${nombreFull}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="${cerrar}"></button>
                                </div>
                                <div class="modal-body text-dark">
                                    <div class="mb-3">
                                        <label for="${codigoEjercicio}nombreEjercicioEj" class="form-label">${textosEjercicio.nuevoNombre}</label>
                                        <input type="text" class="form-control" id="${codigoEjercicio}nombreEjercicioEj" placeholder="${textosEjercicio.nuevoNombreP}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="${codigoEjercicio}descripcionEj" class="form-label">${textosEjercicio.nuevaDescripcion}</label>
                                        <textarea class="form-control" id="${codigoEjercicio}descripcionEj" rows="3" placeholder="${textosEjercicio.nuevaDescripcionP}"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="${codigoEjercicio}musculoTrabajadoEj" class="form-label">${textosEjercicio.nuevoMusculo}</label>
                                        <select class="form-select" id="${codigoEjercicio}musculoTrabajadoEj">
                                            <option selected disabled>${textosEjercicio.musculo}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="javascript:;" id="${codigoEjercicio}btnEliminarEjercicio" class="nav-link active error me-3" aria-current="true">${eliminar}</a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${cerrar}</button>
                                    <button id="${codigoEjercicio}btnEditarEjercicio" type="button" class="btn btn-primary">${confirmar}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);

                ejerciciosCreados.push(codigoEjercicio);
                !tieneRolCliente && editarEjercicio(value);
            }
        } else {
            $(`#${codigoEjercicio}Ejercicio`).hide();
        }
    });

    $.each(tabla, (key, value) => {
        if(value.encontrado == true) {
            found = true;
        }
    })

    /* if(!found) {
        $(`#ejercicioHeader`).hide();
    } */
};

const mostrarPrompt = () => {
    let cancelar = idioma == "spanish" && "Cancelar" || "Cancel"
    let cerrar = idioma == "spanish" && "Cerrar" || "Close"
    let crear = idioma == "spanish" && "Crear" || "Create"

    let textosEjercicio = {
        crearEjercicio: idioma == "spanish" ? "Crear ejercicio" : "Create exercise",

        nombre: idioma == "spanish" ? "Nombre ejercicio" : "Exercise name",
        nombreP: idioma == "spanish" ? "Ingrese el nombre del ejercicio" : "Enter an exercise name",

        descripcion: idioma == "spanish" ? "Description" : "Description",
        descripcionP: idioma == "spanish" ? "Ingrese la descripción del ejercicio" : "Enter an exercise description",

        nuevoMusculo: idioma == "spanish" ? "Músculo trabajado" : "Trained muscle",
        musculo: idioma == "spanish" ? "Seleccione un músculo" : "Select a muscle",
    }
    let musculos = [];

    $(`#modalContainer`).append(`
        <div class="modal fade" id="CrearEjercicio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="exampleModalLabel">${textosEjercicio.crearEjercicio}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="${cerrar}"></button>
                    </div>
                    <div class="modal-body text-dark">
                        <div class="mb-3">
                            <label for="nombreEjercicio" class="form-label">${textosEjercicio.nombre}</label>
                            <input type="text" class="form-control" id="nombreEjercicio" placeholder="${textosEjercicio.nombreP}">
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">${textosEjercicio.descripcion}</label>
                            <textarea class="form-control" id="descripcion" rows="3" placeholder="${textosEjercicio.descripcionP}"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="musculoTrabajado" class="form-label">${textosEjercicio.nuevoMusculo}</label>
                            <select class="form-select" id="musculoTrabajado">
                                <option selected disabled>${textosEjercicio.musculo}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${cancelar}</button>
                        <button id="btnCrearEjercicio" type="button" class="btn btn-primary">${crear}</button>
                    </div>
                </div>
            </div>
        </div>
    `);

    const modal = new bootstrap.Modal($("#CrearEjercicio"));
    modal.show();
}

export const crearEjercicio = () => {
    console.log("promptEjercicio");
    mostrarPrompt();

    solicitarDatosTabla("grupomuscular")
        .then((resultado) => {
            console.log(resultado);

            $("#musculoTrabajado").html("");

            let musculo = idioma == "spanish" && "Seleccione un músculo" || "Select a muscle"
            $("#musculoTrabajado").append(`
                <option selected disabled>${musculo}</option>
            `)

            resultado.forEach(grupomuscular => {
                $("#musculoTrabajado").append(`
                    <option value="${grupomuscular.nombreMusculo}">${grupomuscular.nombreMusculo}</option>
                `)
            });
        })
        .catch((error) => {
            alert(`Error al obtener grupos musculares: ${error}`);
        });

    $("#btnCrearEjercicio").click(() => {
        const nombreEjercicio = $("#nombreEjercicio").val();
        const descripcion = $("#descripcion").val();
        const musculoTrabajado = $("#musculoTrabajado").val();

        console.log(nombreEjercicio, descripcion, musculoTrabajado);

        if (!nombreEjercicio || !descripcion || !musculoTrabajado) {
            alert("Por favor, complete todos los campos.");
            return;
        }

        crearEnTabla("ejercicio", {
            "nombreEjercicio": nombreEjercicio,
            "descripcion": descripcion,
            "musculoTrabajado": musculoTrabajado
        })
            .then((resultado) => {
                alert(resultado[1]);
                if(resultado[0] === true) {
                    cargar();
                    $('#CrearEjercicio').modal('hide');
                    $('#CrearEjercicio').remove();
                }
            })
            .catch((error) => {
                alert(`Error al crear ejercicio: ${error}`);
            });
    })

    //
    //preguntarle al profe de dweb si ta bien, era esto o stackear infinitas funciones una arriba de otra y capaz q lagear
    //
    $('#CrearEjercicio').on('hidden.bs.modal', function () {
        $('#CrearEjercicio').remove();
        $("#btnCrearEjercicio").off("click");
    });
};

export const editarEjercicio = (datos) => {
    let cancelar = idioma == "spanish" && "Cancelar" || "Cancel"
    let confirmar = idioma == "spanish" && "Confirmar" || "Confirm"

    const codigoEjercicio = datos.codigoEjercicio;
    const nombreEjercicioOG = datos.nombreEjercicio;

    solicitarDatosTabla("grupomuscular")
        .then((resultado) => {
            console.log(resultado);

            $(`#${codigoEjercicio}musculoTrabajadoEj`).html("");

            let musculo = idioma == "spanish" && "Seleccione un músculo" || "Select a muscle"
            $(`#${codigoEjercicio}musculoTrabajadoEj`).append(`
                <option selected disabled>${musculo}</option>
            `)

            resultado.forEach(grupomuscular => {
                $(`#${codigoEjercicio}musculoTrabajadoEj`).append(`
                    <option value="${grupomuscular.nombreMusculo}">${grupomuscular.nombreMusculo}</option>
                `)
            });
        })
        .catch((error) => {
            alert(`Error al obtener grupos musculares: ${error}`);
        });

    $(`#${codigoEjercicio}btnEditarEjercicio`).click(() => {
        const nombreEjercicio = $(`#${codigoEjercicio}nombreEjercicioEj`).val();
        const descripcion = $(`#${codigoEjercicio}descripcionEj`).val();
        const musculoTrabajado = $(`#${codigoEjercicio}musculoTrabajadoEj`).val();

        if (!nombreEjercicio || !descripcion || !musculoTrabajado) {
            alert("Por favor, complete todos los campos.");
            return;
        }

        modificarEnTabla("ejercicio", {
            "codigoEjercicio": codigoEjercicio,
            "nombreEjercicio": nombreEjercicio,
            "descripcion": descripcion,
            "musculoTrabajado": musculoTrabajado
        })
            .then((resultado) => {
                alert(resultado[1]);
                if(resultado[0] === true) {
                    ejerciciosCreados[ejerciciosCreados.indexOf(codigoEjercicio)] = null;
                    $(`#${codigoEjercicio}EjercicioView`).remove();
                    $(`#${codigoEjercicio}Ejercicio`).remove();
                    cargar();
                    $(`#${codigoEjercicio}EjercicioEdit`).modal('hide');
                    $(`#${codigoEjercicio}EjercicioEdit`).remove();
                }
            })
            .catch((error) => {
                alert(`Error al actualizar ejercicio: ${error}`);
            });
    })

    let confirmacion = idioma == "spanish" && "¿Estas seguro de que quieres eliminar" || "Are you sure you want to delete"
    $(`#${codigoEjercicio}btnEliminarEjercicio`).click(() => {
        $("#modalContainer").append(`
            <div class="modal fade" id="secondModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-dark" id="exampleModalLabel">${confirmacion} ${nombreEjercicioOG} (ID ${codigoEjercicio})?</h5>
                        </div>
                        <div class="modal-footer">
                            <button id="confirmWipe" type="button" class="btn btn-secondary" data-bs-dismiss="modal">${confirmar}</button>
                            <button id="cancelWipe" type="button" class="btn btn-primary">${cancelar}</button>
                        </div>
                    </div>
                </div>
            </div>
        `);

        $('#secondModal').modal('show');
        $(`#${codigoEjercicio}EjercicioEdit`).modal('hide');

        $("#confirmWipe").click(()  => {
            eliminarEnTabla("ejercicio", {
                "codigoEjercicio": codigoEjercicio
            })
                .then((resultado) => {
                    alert(resultado[1]);
                    if(resultado[0] === true) {
                        ejerciciosCreados[ejerciciosCreados.indexOf(codigoEjercicio)] = null;
                        $(`#${codigoEjercicio}EjercicioView`).remove();
                        cargar();
                        $(`#${codigoEjercicio}Ejercicio`).remove();
                        $(`#${codigoEjercicio}EjercicioEdit`).modal('hide');
                        $(`#${codigoEjercicio}EjercicioEdit`).remove();
                    }
                })
                .catch((error) => {
                    alert(`Error al eliminar grupo muscular: ${error}`);
                });
        })

        $("#cancelWipe").click(()  => {
            $("#confirmWipe").off("click");

            $(`#${codigoEjercicio}EjercicioEdit`).modal('show');
            $('#secondModal').modal('hide');
            $('#secondModal').remove();
        })

        $('#secondModal').on('hidden.bs.modal', function () {
            $(`#${codigoEjercicio}EjercicioEdit`).modal('show');
            $('#secondModal').remove();
            $("#confirmWipe").off("click");
        });
    })
}