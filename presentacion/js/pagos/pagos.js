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