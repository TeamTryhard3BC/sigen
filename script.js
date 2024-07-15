//este JS es temporal, funciona correctamente pero sirve solo
//para probar las funcionalidades de los botones de bootstrap, usar jQuery cuando se solicite

function elegirIdioma(btnId) {
    const botonClickeado = document.getElementById(btnId);
    const botones = document.querySelectorAll('.btn');

    botones.forEach(boton => {
        if (boton != botonClickeado) {
            boton.classList.remove('active');
        } else {
            boton.classList.add('active');
        }
    });

    //console.log(btnId);
    return
}

function aceptar() {
    const botones = document.querySelectorAll('.btn');
    let found = false;

    botones.forEach(boton => {
        if (boton.id != "btnAceptar") {
            if (boton.classList.contains('active')) {
                alert("elegido " + boton.id);
                found = true;
                window.location.href = "personalizacion1.html";
                return
            }
        }
    });

    if (!found) {
        alert("no elegiste ningun idioma");
    }
    
    return
}

