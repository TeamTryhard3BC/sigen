$("#btn1").click((event) => {
    event.preventDefault();
    const language = $("html").attr("lang");

    console.log("1");

    window.location = `http://localhost/proyectosigen/presentacion/html/${language}/pago.html`;
})

$("#btn2").click((event) => {
    event.preventDefault();
    const language = $("html").attr("lang");

    console.log("2");

    window.location = `http://localhost/proyectosigen/presentacion/html/${language}/pago.html`;
})

$("#btn3").click((event) => {
    event.preventDefault();
    const language = $("html").attr("lang");

    console.log("3");

    window.location = `http://localhost/proyectosigen/presentacion/html/${language}/pago.html`;
})


$("#btnSuscribir").click(function () {
    const modalHTML = `
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Formulario de Pagos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="pagoForm">
                        <div class="mb-3">
                            <label for="usuarios" class="form-label">Cliente a calificar</label>
                                <select class="form-select" id="metodoPago" name="metodoPago" required>
                                    <option value="debito">Débito</option>
                                    <option value="credito">Crédito</option>
                                    <option value="efectivo">Efectivo</option>
                                </select>
                            <label for="pagos"><em> (Puedes seleccionar uno solo)</em></label>
                        </div>
                        <div class="mb-3">
                            <label for="pago" class="form-label">Pago:</label>
                            <input type="number" id="pago" name="pago" class="form-control" min="1" max="2" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    `;

});