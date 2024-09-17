let idioma = "spanish";

const elegirIdioma = (btnId) => {
    if (!btnId) return

    const botones = document.querySelectorAll('.btn');
    
    botones.forEach(boton => {
        if (boton.id != btnId) {
            boton.classList.remove('active');
        } else {
            boton.classList.add('active');
            idioma = btnId;
        }
    });

    return
}

const confirmarIdioma = () => {
    //alert("elegido " + idioma);
    window.location.href = `${idioma}/personalizacion1.html`;

    return
}

$("#spanish").click(() => elegirIdioma("spanish"));
$("#english").click(() => elegirIdioma("english"));

$("#btnAceptarIdioma").click(confirmarIdioma);