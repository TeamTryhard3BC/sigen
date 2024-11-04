import { BASE_PATH } from '../BASE_PATH.js';
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
    const archivo = BASE_PATH + `/datos/config.json`;

    fetch(archivo, {
        method: 'HEAD'
    })
        .then((response) => {
            if (response.ok) {
                window.location.href = `${idioma}/index.html`;
                return
            } else {
                //alert("elegido " + idioma);

                window.location.href = `${idioma}/personalizacion1.html`;
                return
            }
        })
        .catch((error) => {
            console.log('Error:', error);
        });
}

$("#spanish").click(() => elegirIdioma("spanish"));
$("#english").click(() => elegirIdioma("english"));

$("#btnAceptarIdioma").click(confirmarIdioma);