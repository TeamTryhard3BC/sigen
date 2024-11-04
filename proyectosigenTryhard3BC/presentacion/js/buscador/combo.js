import { solicitarDatosTabla, crearEnTabla, modificarEnTabla, eliminarEnTabla } from "../funcionesUtiles.js";
import { tieneRolCliente, idioma, maxNombre, maxDescripcion, cargar } from "./buscadorMain.js";

let combosCreados = []

export const combo = (textoBuscador, tabla) => {
    let found = false;

    let verMas = idioma == "spanish" && "Ver más" || "View more"
    let editar = idioma == "spanish" && "Editar" || "Edit"
    let editando = idioma == "spanish" && "Editando" || "Editing"
    let cerrar = idioma == "spanish" && "Cerrar" || "Close"
    let guardar = idioma == "spanish" && "Guardar cambios" || "Save changes"
    let confirmar = idioma == "spanish" && "Confirmar cambios" || "Confirm changes"
    let eliminar = idioma == "spanish" && "Eliminar" || "Delete"
    let cancelar = idioma == "spanish" && "Cancelar" || "Cancel"
    let multiple = idioma == "spanish" && "Puedes elegir múltiples" || "You can select multiple"

    let textosCombo = {
        listar: idioma == "spanish" ? "Ejercicios del combo" : "Combo exercises",
        crearCombo: idioma == "spanish" ? "Crear combo" : "Create combo",
        nombreEdit: idioma == "spanish" ? "Nuevo nombre de combo" : "New combo name",
        nombreEditP: idioma == "spanish" ? "Ingrese el nuevo nombre del combo" : "Enter a new combo name",
        descriptionEdit: idioma == "spanish" ? "Nueva descripción" : "New combo name",
        descriptionEditP: idioma == "spanish" ? "Ingrese la nueva descripción del combo" : "Enter a new combo description",
        nuevosEjercicios: idioma == "spanish" ? "Nuevos ejercicios" : "New exercises",
    }

    let btnHide = tieneRolCliente && "hidden" || null

    $.each(tabla, (key, value) => {
        const codigoCombo = value.codigoCombo;
        const nombreFull = value.nombreCombo;
        const descripcionFull = value.descripcion;
        value.encontrado = false;

        let nombreAcortado = nombreFull.substr(0, maxNombre);
        let descripcionAcortada = descripcionFull.substr(0, maxDescripcion);

        if (nombreFull.length > maxNombre) {
            nombreAcortado = nombreAcortado + "...";
        }
        
        if (descripcionFull.length > maxDescripcion) {
            descripcionAcortada = descripcionAcortada + "...";
        }

        //console.log(textoBuscador);

        const nombreMinusculas = nombreFull.toLowerCase();
        const descripcionMinusculas = descripcionFull.toLowerCase();

        if (textoBuscador == "" || nombreMinusculas.indexOf(textoBuscador) !== -1 || descripcionMinusculas.indexOf(textoBuscador) !== -1) {
            $(`#comboHeader`).show();
            $(`#${codigoCombo}Combo`).show();

            value.encontrado = true;

            //<img src="../../media/150.png" class="card-img" alt="Image Description">

            if(!combosCreados.includes(codigoCombo)) {
                $(`#comboContenedor`).append(`
                    <div class="col" id="${codigoCombo}Combo">
                        <div class="card card-horizontal">
                                <div class="card-body">
                                    <h5 class="card-title">${nombreAcortado}</h5>
                                    <p class="card-text">${descripcionAcortada}</p>
                                    <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#${codigoCombo}ComboView">${verMas}</a>
                                </div>
                            <div class="card-footer text-muted">
                                <a href="javascript:;" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#${codigoCombo}ComboEdit" ${btnHide}>${editar}</a>
                            </div>
                        </div>
                    </div>
                `);

                $(`#modalContainer`).append(`
                    <div class="modal fade" id="${codigoCombo}ComboView" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-dark" id="exampleModalLabel">${nombreFull}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="${cerrar}"></button>
                                </div>
                                <div class="modal-body text-dark">
                                    ${descripcionFull}
                                </div>
                                <div class="mb-3">
                                    <label for="${codigoCombo}ComboVerEjs" class="form-label text-dark">${textosCombo.listar}</label>
                                    <select class="form-select select-auto-size" multiple id="${codigoCombo}ComboVerEjs">
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${cerrar}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal fade" id="${codigoCombo}ComboEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-dark" id="exampleModalLabel">${editando} ${nombreFull}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="${cerrar}"></button>
                                </div>
                                <div class="modal-body text-dark">
                                        <div class="mb-3">
                                            <label for="${codigoCombo}nombreCombo" class="form-label">${textosCombo.nombreEdit}</label>
                                            <input type="text" class="form-control" id="${codigoCombo}nombreCombo" placeholder="${textosCombo.nombreEditP}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="${codigoCombo}descripcionCombo" class="form-label">${textosCombo.descriptionEdit}</label>
                                            <textarea class="form-control" id="${codigoCombo}descripcionCombo" rows="3" placeholder="${textosCombo.descriptionEditP}"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="${codigoCombo}ejerciciosCombo" class="form-label">${textosCombo.nuevosEjercicios}</label>
                                            <select class="form-select select-auto-size" multiple id="${codigoCombo}ejerciciosCombo" name="ejercicios[]" placeholder="">
                                            </select>
                                            <label for="${codigoCombo}ejerciciosCombo"><em> (${multiple})</em></label>
                                        </div>
                                </div>
                                
                                <div class="modal-footer">
                                    <a href="javascript:;" id="${codigoCombo}btnEliminarCombo" class="nav-link active error me-3" aria-current="true">${eliminar}</a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${cerrar}</button>
                                    <button id="${codigoCombo}btnEditarCombo" type="button" class="btn btn-primary">${confirmar}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);

                combosCreados.push(codigoCombo);
                !tieneRolCliente && editarCombo(value);
            }
        } else {
            $(`#${codigoCombo}Combo`).hide();
        }
    });

    $.each(tabla, (key, value) => {
        if(value.encontrado == true) {
            found = true;
        }
    })

    solicitarDatosTabla("forma")
        .then((resultadoForma) => {
            console.log(resultadoForma);

            solicitarDatosTabla("ejercicio")
                .then((resultadoEjercicios) => {
                    console.log(resultadoEjercicios);

                    $.each(tabla, (key, value) => {
                        $(`#${value.codigoCombo}ComboVer`).html("");
                        let totalEjercicios = 0;

                        resultadoForma.forEach(vinculoComboEjercicio => {
                            if(vinculoComboEjercicio.codigoCombo == value.codigoCombo) {
                                let nombreEjercicio;

                                resultadoEjercicios.forEach(datosEjercicio => {
                                    if(vinculoComboEjercicio.codigoEjercicio == datosEjercicio.codigoEjercicio) {
                                        nombreEjercicio = datosEjercicio.nombreEjercicio;
                                        totalEjercicios += 1;
                                    }
                                })
    
                                $(`#${value.codigoCombo}ComboVerEjs`).append(`
                                    <option value="" disabled>(ID ${vinculoComboEjercicio.codigoEjercicio}) ${nombreEjercicio}</option>
                                `)
                            }
                        });

                        console.log(totalEjercicios)
                        $(`#${value.codigoCombo}ComboVerEjs`).attr("size", totalEjercicios);
                    })
                })

                .catch((error) => {
                    alert(`Error al obtener ejercicios: ${error}`);
                });
            })
        .catch((error) => {
            alert(`Error al obtener datos de tabla forma: ${error}`);
        });

    /* if(!found) {
        $(`#comboHeader`).hide();
    } */
};

const mostrarPrompt = () => {
    let cancelar = idioma == "spanish" && "Cancelar" || "Cancel"
    let cerrar = idioma == "spanish" && "Cerrar" || "Close"
    let crear = idioma == "spanish" && "Crear" || "Create"
    let multiple = idioma == "spanish" && "Puedes elegir múltiples" || "You can select multiple"

    let textosCombo = {
        crearCombo: idioma == "spanish" ? "Crear combo" : "Create combo",
        nombreCrear: idioma == "spanish" ? "Nombre de combo" : "Combo name",
        nombreCrearP: idioma == "spanish" ? "Ingrese nombre del combo" : "Enter a combo name",
        descriptionCrear: idioma == "spanish" ? "Descripción" : "Description",
        descriptionCrearP: idioma == "spanish" ? "Ingrese la descripción del combo" : "Enter a combo description",
        ejercicios: idioma == "spanish" ? "Ejercicios" : "Exercises"
    }
    let musculos = [];

    $(`#modalContainer`).append(`
        <div class="modal fade" id="CrearCombo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="exampleModalLabel">${textosCombo.crearCombo}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="${cerrar}"></button>
                    </div>
                    <div class="modal-body text-dark">
                        <div class="mb-3">
                            <label for="nombreCombo" class="form-label">${textosCombo.nombreCrear}</label>
                            <input type="text" class="form-control" id="nombreCombo" placeholder="${textosCombo.nombreCrearP}">
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">${textosCombo.descriptionCrear}</label>
                            <textarea class="form-control" id="descripcion" rows="3" placeholder="${textosCombo.descriptionCrearP}"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="ejercicios" class="form-label">${textosCombo.ejercicios}</label>
                            <select class="form-select select-auto-size" multiple id="ejercicios" name="ejercicios[]" placeholder="">
                            </select>
                            <label for="ejercicios"><em> (${multiple})</em></label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${cancelar}</button>
                        <button id="btnCrearCombo" type="button" class="btn btn-primary">${crear}</button>
                    </div>
                </div>
            </div>
        </div>
    `);

    const modal = new bootstrap.Modal($("#CrearCombo"));
    modal.show();
}

export const crearCombo = () => {
    console.log("promptCombo");
    mostrarPrompt();

    solicitarDatosTabla("ejercicio")
        .then((resultado) => {
            console.log(resultado);

            $("#ejercicios").html("");
            let select = idioma == "spanish" && "Seleccione un ejercicio" || "Select an exercise"

            $("#ejercicios").append(`
                <option selected disabled>${select}</option>
            `)
            resultado.forEach(ejercicio => {
                $("#ejercicios").append(`
                    <option value="${ejercicio.codigoEjercicio}">(ID ${ejercicio.codigoEjercicio}) ${ejercicio.nombreEjercicio}</option>
                `)
            });
        
            $("#ejercicios").attr("size", resultado.length + 1);
        })
        .catch((error) => {
            alert(`Error al obtener ejercicios: ${error}`);
        });

    $("#btnCrearCombo").click(() => {
        const nombreCombo = $("#nombreCombo").val();
        const descripcion = $("#descripcion").val();
        const ejercicios = $("#ejercicios").val();

        if (!nombreCombo || !descripcion || !ejercicios || ejercicios.length <= 0) {
            alert("Por favor, complete todos los campos.");
            return;
        }

        crearEnTabla("combo", {
            "nombreCombo": nombreCombo,
            "descripcion": descripcion,
            "ejercicios": ejercicios
        })
            .then((resultado) => {
                alert(resultado[1]);
                if(resultado[0] === true) {
                    cargar();
                    $('#CrearCombo').modal('hide');
                    $('#CrearCombo').remove();
                }
            })
            .catch((error) => {
                alert(`Error al crear combo: ${error}`);
            });
    })

    //
    //preguntarle al profe de dweb si ta bien, era esto o stackear infinitas funciones una arriba de otra y capaz q lagear
    //
    $('#CrearCombo').on('hidden.bs.modal', function () {
        $('#CrearCombo').remove();
        $("#btnCrearCombo").off("click");
    });
};

export const editarCombo = (datos) => {
    let cancelar = idioma == "spanish" && "Cancelar" || "Cancel"
    let confirmar = idioma == "spanish" && "Confirmar" || "Confirm"

    const codigoCombo = datos.codigoCombo;
    const nombreComboOG = datos.nombreCombo;

    $(`#${codigoCombo}ComboEdit`).on('show.bs.modal', function (event) {
        solicitarDatosTabla("ejercicio")
        .then((resultado) => {
            console.log(resultado);
            let select = idioma == "spanish" && "Seleccione un ejercicio" || "Select an exercise"

            $(`#${codigoCombo}ejerciciosCombo`).html("");
            $(`#${codigoCombo}ejerciciosCombo`).append(`
                <option selected disabled>${select}</option>
            `)

            resultado.forEach(ejercicio => {
                $(`#${codigoCombo}ejerciciosCombo`).append(`
                    <option value="${ejercicio.codigoEjercicio}">(ID ${ejercicio.codigoEjercicio}) ${ejercicio.nombreEjercicio}</option>
                `)
            });
        })
        .catch((error) => {
            alert(`Error al obtener ejercicios: ${error}`);
        });
    });

    $(`#${codigoCombo}btnEditarCombo`).click(() => {
        const nombreCombo = $(`#${codigoCombo}nombreCombo`).val();
        const descripcion = $(`#${codigoCombo}descripcionCombo`).val();
        const ejercicios = $(`#${codigoCombo}ejerciciosCombo`).val();

        if (!nombreCombo || !descripcion || !ejercicios || ejercicios.length <= 0) {
            alert("Por favor, complete todos los campos.");
            return;
        }

        modificarEnTabla("combo", {
            "codigoCombo": codigoCombo,
            "nombreCombo": nombreCombo,
            "descripcion": descripcion,
            "ejercicios": ejercicios
        })
            .then((resultado) => {
                alert(resultado[1]);
                if(resultado[0] === true) {
                    combosCreados[combosCreados.indexOf(codigoCombo)] = null;
                    $(`#${codigoCombo}ComboView`).remove();
                    $(`#${codigoCombo}Combo`).remove();
                    cargar();
                    $(`#${codigoCombo}ComboEdit`).modal('hide');
                    $(`#${codigoCombo}ComboEdit`).remove();
                }
            })
            .catch((error) => {
                alert(`Error al actualizar combo: ${error}`);
            });
    })

    let confirmacion = idioma == "spanish" && "¿Estas seguro de que quieres eliminar" || "Are you sure you want to delete"
    $(`#${codigoCombo}btnEliminarCombo`).click(() => {
        $("#modalContainer").append(`
            <div class="modal fade" id="secondModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-dark" id="exampleModalLabel">${confirmacion} ${nombreComboOG} (ID ${codigoCombo})?</h5>
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
        $(`#${codigoCombo}ComboEdit`).modal('hide');

        $("#confirmWipe").click(()  => {
            eliminarEnTabla("combo", {
                "codigoCombo": codigoCombo
            })
                .then((resultado) => {
                    alert(resultado[1]);
                    if(resultado[0] === true) {
                        combosCreados[combosCreados.indexOf(codigoCombo)] = null;
                        $(`#${codigoCombo}ComboView`).remove();
                        cargar();
                        $(`#${codigoCombo}Combo`).remove();
                        $(`#${codigoCombo}ComboEdit`).modal('hide');
                        $(`#${codigoCombo}ComboEdit`).remove();
                    }
                })
                .catch((error) => {
                    alert(`Error al eliminar combo: ${error}`);
                });
        })

        $("#cancelWipe").click(()  => {
            $("#confirmWipe").off("click");

            $(`#${codigoCombo}ComboEdit`).modal('show');
            $('#secondModal').modal('hide');
            $('#secondModal').remove();
        })

        $('#secondModal').on('hidden.bs.modal', function () {
            $(`#${codigoCombo}ComboEdit`).modal('show');
            $('#secondModal').remove();
            $("#confirmWipe").off("click");
        });
    })
}