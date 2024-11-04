import { BASE_PATH } from './BASE_PATH.js';

export const getDatosUsuario = () => {
    return new Promise((resolve, reject) => {
        let url = BASE_PATH + '/negocio/modules/requests.php'

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                funcion: "getDatosUsuario",
                clase: "funcionesUtiles"
            },
            success: function(data) {
                const retorno = JSON.parse(data);
                resolve(retorno);
            },
            error: function(error) {
                reject(error);
            }
        });
    });
};

export const getConfiguracionGimnasio = () => {
    return new Promise((resolve, reject) => {
        let url = BASE_PATH + '/negocio/modules/requests.php'

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                funcion: "getConfiguracionGimnasio",
                clase: "funcionesUtiles"
            },
            success: function(data) {
                const retorno = JSON.parse(data);
                resolve(retorno);
            },
            error: function(error) {
                reject(error);
            }
        });
    });
};

export const getDatosPersona = () => {
    return new Promise((resolve, reject) => {
        let url = BASE_PATH + '/negocio/modules/requests.php'

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                funcion: "getDatosPersona",
                clase: "funcionesUtiles"
            },
            success: function(data) {
                const retorno = JSON.parse(data);
                resolve(retorno);
            },
            error: function(error) {
                reject(error);
            }
        });
    });
};

export const getDatosUsuarios = () => {
    return new Promise((resolve, reject) => {
        let url = BASE_PATH + '/negocio/modules/requests.php'; 

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                funcion: "getDatosUsuario", 
                codigoPersona: codigoPersona
            },
            success: function(data) {
                const retorno = JSON.parse(data);
                resolve(retorno);
            },
            error: function(error) {
                reject(error);
            }
        });
    });
};

export const solicitarDatosTabla = (tabla) => {
    return new Promise((resolve, reject) => {
        let url = BASE_PATH + '/negocio/modules/requests.php'

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                funcion: "getDatosTabla",
                clase: "funcionesUtiles",
                tabla: tabla,
            },
            success: function(data) {
                console.log(data);
                const retorno = JSON.parse(data);
                resolve(retorno);
            },
            error: function(error) {
                reject(error);
            }
        });
    });
};

export const crearEnTabla = (tabla, datos) => {
    return new Promise((resolve, reject) => {
        let url = BASE_PATH + '/negocio/modules/requests.php'

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                funcion: "crearEnTabla",
                clase: "funcionesUtiles",
                tabla: tabla,
                datos: datos
            },
            success: function(data) {
                console.log(data);
                const retorno = JSON.parse(data);
                resolve(retorno);
            },
            error: function(error) {
                reject(error);
            }
        });
    });
}

export const modificarEnTabla = (tabla, datos) => {
    return new Promise((resolve, reject) => {
        let url = BASE_PATH + '/negocio/modules/requests.php'

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                funcion: "modificarEnTabla",
                clase: "funcionesUtiles",
                tabla: tabla,
                datos: datos
            },
            success: function(data) {
                console.log(data);
                const retorno = JSON.parse(data);
                resolve(retorno);
            },
            error: function(error) {
                reject(error);
            }
        });
    });
}

export const eliminarEnTabla = (tabla, datos) => {
    return new Promise((resolve, reject) => {
        let url = BASE_PATH + '/negocio/modules/requests.php'

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                funcion: "eliminarEnTabla",
                clase: "funcionesUtiles",
                tabla: tabla,
                datos: datos
            },
            success: function(data) {
                console.log(data);
                const retorno = JSON.parse(data);
                resolve(retorno);
            },
            error: function(error) {
                reject(error);
            }
        });
    });
}

export const obtenerUsuarios  = () => {
    return new Promise((resolve, reject) => {
        let url = BASE_PATH + '/negocio/logica/getUsuarios.php'

        $.ajax({
            url: url, 
            method: 'POST',
            success: function (data) {
                resolve(data);
            },
            error: function(error){
                reject(error);
            }
        });
    });
}

export const obtenerDeportes  = () => {
    return new Promise((resolve, reject) => {
        let url = BASE_PATH + '/negocio/logica/getDeportes.php'

        $.ajax({
            url: url, 
            method: 'POST',
            success: function (data) {
                resolve(data);
            },
            error: function(error){
                reject(error);
            }
        });
    });
}

export const obtenerCombos  = () => {
    return new Promise((resolve, reject) => {
        let url = BASE_PATH + '/negocio/logica/getCombos.php'

        $.ajax({
            url: url,
            method: 'POST',
            success: function (data) {
                resolve(data);
            },
            error: function(error){
                reject(error);
            }
        });
    });
}

export const obtenerSugerenciasDeportesPorCombos  = () => {
    return new Promise((resolve, reject) => {
        let url = BASE_PATH + '/negocio/logica/sugerenciasDeportes.php'

        $.ajax({
            url: url, 
            method: 'POST',
            success: function (data) {
                resolve(data);
            },
            error: function(error){
                reject(error);
            }
        });
    });
}



export const getCalificacionEstado  = () => {
    return new Promise((resolve, reject) => {
        let url = BASE_PATH + '/negocio/logica/getCalificacionEstado.php'

        $.ajax({
            url: url, 
            method: 'POST',
            success: function (data) {
                resolve(data);
            },
            error: function(error){
                reject(error);
            }
        });
    });
}

export const getCombosRealizados  = () => {
    return new Promise((resolve, reject) => {
        let url = BASE_PATH + '/negocio/logica/getCombosRealizados.php'

        $.ajax({
            url: url, 
            method: 'POST',
            success: function (data) {
                resolve(data);
            },
            error: function(error){
                reject(error);
            }
        });
    });
}