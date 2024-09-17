const checkLogin = () => {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: 'http://localhost/proyectosigen/negocio/modules/requests.php',
            method: 'GET',
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