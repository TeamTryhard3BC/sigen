import { solicitarDatosTabla, crearEnTabla, modificarEnTabla, eliminarEnTabla } from "../funcionesUtiles.js";
import { tieneRolCliente, idioma, maxNombre, maxDescripcion, cargar } from "./buscadorMain.js";

let gruposMuscularesCreados = []

export const grupomuscular = (textoBuscador, tabla) => {
    let found = false;
    let btnHide = tieneRolCliente && "hidden" || null

    $.each(tabla, (key, value) => {
        const nombreFull = value.nombreMusculo;
        value.encontrado = false;

        let nombreAcortado = nombreFull.substr(0, maxNombre);

        if (nombreFull.length > maxNombre) {
            nombreAcortado = nombreAcortado + "...";
        }
    
        const nombreMinusculas = nombreFull.toLowerCase();

        //console.log(textoBuscador);

        if (textoBuscador == "" || nombreMinusculas.indexOf(textoBuscador) !== -1) {
            $(`#grupomuscularHeader`).show();
            $(`#${nombreFull}GrupoMuscular`).show();
            value.encontrado = true;

            let verMas = idioma == "spanish" && "Ver más" || "View more"
            let editar = idioma == "spanish" && "Editar" || "Edit"
            let editando = idioma == "spanish" && "Editando" || "Editing"
            let cerrar = idioma == "spanish" && "Cerrar" || "Close"
            let guardar = idioma == "spanish" && "Guardar cambios" || "Save changes"
            let confirmar = idioma == "spanish" && "Confirmar cambios" || "Confirm changes"
            let eliminar = idioma == "spanish" && "Eliminar" || "Delete"
            let cancelar = idioma == "spanish" && "Cancelar" || "Cancel"

            let textosMusculos = {
                nuevoNombre: idioma == "spanish" ? "Nuevo nombre del grupo muscular" : "New muscle group name",
                nuevoNombreP: idioma == "spanish" ? "Ingrese el nuevo nombre del grupo muscular" : "Enter a new muscle group name",
            }

            //console.log(nombreFull);

            if(!gruposMuscularesCreados.includes(nombreFull)) {
                $(`#grupomuscularContenedor`).append(`
                    <div class="col" id="${nombreFull}GrupoMuscular">
                        <div class="card card-horizontal">
                                <div class="card-body">
                                    <h5 class="card-title">${nombreFull}</h5>
                                </div>
                            <div class="card-footer text-muted">
                                <a href="javascript:;" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#${nombreFull}GrupoMuscularEdit" ${btnHide}>${editar}</a>
                            </div>
                        </div>
                    </div>
                `);

                //MODAL PARA EDITAR
                $(`#modalContainer`).append(`
                    <div class="modal fade" id="${nombreFull}GrupoMuscularEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-dark" id="exampleModalLabel">${editando} ${nombreFull}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="${cerrar}"></button>
                                </div>
                                <div class="modal-body text-dark">
                                    <div class="mb-3">
                                        <label for="${nombreFull}MusculoEdit" class="form-label">${textosMusculos.nuevoNombre}</label>
                                        <input type="text" class="form-control" id="${nombreFull}MusculoEdit" placeholder="${textosMusculos.nuevoNombreP}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="javascript:;" id="${nombreFull}btnEliminarGrupoMuscular" class="nav-link active error me-3" aria-current="true">${eliminar}</a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${cancelar}</button>
                                    <button id="${nombreFull}btnEditarGrupoMuscular" type="button" class="btn btn-primary">${confirmar}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);

                gruposMuscularesCreados.push(nombreFull);
                !tieneRolCliente && editarGrupoMuscular(value);
            }
        } else {
            $(`#${nombreFull}GrupoMuscular`).hide();
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

    let textosMusculos = {
        crearMusculo: idioma == "spanish" ? "Crear grupo muscular" : "Create muscle group",

        nombre: idioma == "spanish" ? "Nombre del grupo muscular" : "Muscle group name",
        nombreP: idioma == "spanish" ? "Ingrese el nombre del grupo muscular" : "Enter a muscle group name",
    }

    //PROMPT CREAR
    $(`#modalContainer`).append(`
        <div class="modal fade" id="CrearGrupoMuscular" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="exampleModalLabel">${textosMusculos.crearMusculo}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="${cerrar}"></button>
                    </div>
                    <div class="modal-body text-dark">
                        <div class="mb-3">
                            <label for="nombreMusculoCrear" class="form-label">${textosMusculos.nombre}</label>
                            <input type="text" class="form-control" id="nombreMusculoCrear" placeholder="${textosMusculos.nombreP}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${cancelar}</button>
                        <button id="btnCrearGrupoMuscular" type="button" class="btn btn-primary">${crear}</button>
                    </div>
                </div>
            </div>
        </div>
    `);

    const modal = new bootstrap.Modal($("#CrearGrupoMuscular"));
    modal.show();
}

export const crearGrupoMuscular = () => {
    console.log("promptGrupoMuscular");
    mostrarPrompt();

    $("#btnCrearGrupoMuscular").click(() => {
        const nombreMusculo = $("#nombreMusculoCrear").val();

        if (!nombreMusculo) {
            alert("Por favor, complete todos los campos.");
            return;
        }

        crearEnTabla("grupomuscular", {
            "nombreMusculo": nombreMusculo
        })
            .then((resultado) => {
                alert(resultado[1]);
                if(resultado[0] === true) {
                    cargar();
                    $('#CrearGrupoMuscular').modal('hide');
                    $('#modalContainer').html("");
                }
            })
            .catch((error) => {
                alert(`Error al crear grupo muscular: ${error}`);
            });
    })

    //
    //preguntarle al profe de dweb si ta bien, era esto o stackear infinitas funciones una arriba de otra y capaz q lagear
    //
    $('#CrearGrupoMuscular').on('hidden.bs.modal', function () {
        $('#CrearGrupoMuscular').remove();
        $("#btnCrearGrupoMuscular").off("click");
    });
};

export const editarGrupoMuscular = (datos) => {
    let cancelar = idioma == "spanish" && "Cancelar" || "Cancel"
    let confirmar = idioma == "spanish" && "Confirmar" || "Confirm"

    const nombreFull = datos.nombreMusculo;

    $(`#${nombreFull}btnEditarGrupoMuscular`).click(() => {
        const nombreMusculo = $(`#${nombreFull}MusculoEdit`).val();

        if (!nombreMusculo) {
            alert("Por favor, complete todos los campos.");
            return;
        }

        modificarEnTabla("grupomuscular", {
            "nombreMusculoOG": nombreFull,
            "nombreMusculo": nombreMusculo
        })
            .then((resultado) => {
                alert(resultado[1]);
                if(resultado[0] === true) {
                    cargar();
                    $(`#${nombreFull}GrupoMuscular`).remove();
                    $(`#${nombreFull}GrupoMuscularEdit`).modal('hide');
                    $(`#${nombreFull}GrupoMuscularEdit`).remove();
                }
            })
            .catch((error) => {
                alert(`Error al actualizar grupo muscular: ${error}`);
            });
    })

    let confirmacion = idioma == "spanish" && "¿Estas seguro de que quieres eliminar" || "Are you sure you want to delete"
    $(`#${nombreFull}btnEliminarGrupoMuscular`).click(() => {
        $("#modalContainer").append(`
            <div class="modal fade" id="secondModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-dark" id="exampleModalLabel">${confirmacion} ${nombreFull}?</h5>
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
        $(`#${nombreFull}GrupoMuscularEdit`).modal('hide');

        $("#confirmWipe").click(()  => {
            eliminarEnTabla("grupomuscular", {
                "nombreMusculo": nombreFull
            })
                .then((resultado) => {
                    alert(resultado[1]);
                    if(resultado[0] === true) {
                        cargar();
                        $(`#${nombreFull}GrupoMuscular`).remove();
                        $(`#${nombreFull}GrupoMuscularEdit`).modal('hide');
                        $(`#${nombreFull}GrupoMuscularEdit`).remove();
                        gruposMuscularesCreados[gruposMuscularesCreados.indexOf(nombreFull)] = null;
                    }
                })
                .catch((error) => {
                    alert(`Error al eliminar grupo muscular: ${error}`);
                });
        })

        $("#cancelWipe").click(()  => {
            $("#confirmWipe").off("click");

            $(`#${nombreFull}GrupoMuscularEdit`).modal('show');
            $('#secondModal').modal('hide');
            $('#secondModal').remove();
        })

        $('#secondModal').on('hidden.bs.modal', function () {
            $(`#${nombreFull}GrupoMuscularEdit`).modal('show');
            $('#secondModal').remove();
            $("#confirmWipe").off("click");
        });
    })
}