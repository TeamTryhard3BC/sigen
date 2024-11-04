import { BASE_PATH } from '../BASE_PATH.js';

export const checkLogin = () => {
    return new Promise((resolve, reject) => {
        let url = BASE_PATH + '/negocio/modules/requests.php'

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                fileName: "checkLogin",
            },
            success: function(data) {
                console.log(data);
                const retorno = JSON.parse(data);
                console.log(retorno);

                resolve(retorno);
            },
            error: function(error) {
                console.log(error);
                reject(error);
            }
        });
    });
};