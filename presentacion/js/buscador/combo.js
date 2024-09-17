let combosCreados = []

const combo = (textoBuscador, tabla) => {
    let found = false;

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

        console.log(textoBuscador);

        const nombreMinusculas = nombreFull.toLowerCase();
        const descripcionMinusculas = descripcionFull.toLowerCase();

        if (textoBuscador == "" || nombreMinusculas.indexOf(textoBuscador) !== -1 || descripcionMinusculas.indexOf(textoBuscador) !== -1) {
            $(`#comboHeader`).show();
            $(`#${nombreFull}Combo`).show();

            value.encontrado = true;

            let verMas = idioma == "spanish" && "Ver más" || "View more"
            let editar = idioma == "spanish" && "Editar" || "Edit"
            let editando = idioma == "spanish" && "Editando" || "Editing"
            let cerrar = idioma == "spanish" && "Cerrar" || "Close"
            let guardar = idioma == "spanish" && "Guardar cambios" || "Save changes"

            if(!combosCreados.includes(nombreFull)) {
                $(`#comboContenedor`).append(`
                    <div class="col" id="${nombreFull}Combo">
                        <div class="card card-horizontal">
                            <img src="https://via.placeholder.com/150" class="card-img" alt="Image Description">
                                <div class="card-body">
                                    <h5 class="card-title">${nombreAcortado}</h5>
                                    <p class="card-text">${descripcionAcortada}</p>
                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#${nombreFull}View">${verMas}</a>
                                </div>
                            <div class="card-footer text-muted">
                                <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#${nombreFull}Edit">${editar}</a>
                            </div>
                        </div>
                    </div>
                `);
    
                $(`#modalContainer`).append(`
                    <div class="modal fade" id="${nombreFull}View" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-dark" id="exampleModalLabel">${nombreFull}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="${cerrar}"></button>
                                </div>
                                <div class="modal-body text-dark">
                                    ${descripcionFull}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${cerrar}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal fade" id="${nombreFull}Edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-dark" id="exampleModalLabel">${editando} ${nombreFull}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="${cerrar}"></button>
                                </div>
                                <div class="modal-body text-dark">
                                    ${descripcionFull}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${cerrar}</button>
                                    <button type="button" class="btn btn-primary">${guardar}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);

                combosCreados.push(nombreFull);
            }
        } else {
            $(`#${nombreFull}Combo`).hide();
        }
    });

    $.each(tabla, (key, value) => {
        if(value.encontrado == true) {
            found = true;
        }
    })

    if(!found) {
        $(`#comboHeader`).hide();
    }
};