import { getDatosUsuario } from "../funcionesUtiles.js"
import { BASE_PATH } from '../BASE_PATH.js';

export let tieneRolCliente = null;

$(() => {
    getDatosUsuario()
        .then((resultado) => {
            console.log(resultado);

            tieneRolCliente = (resultado.rol === "Cliente")
            console.log(tieneRolCliente)

            cargar()
        })
        .catch((error) => {
            alert('Error al obtener datos de usuario: ' + error)
        });
});

const cargar = () => {
    let codigoPersona

    getDatosUsuario()
        .then((resultado) => {
            console.log(resultado)
            codigoPersona = resultado.codigoPersona
        })
        .catch((error) => {
            alert('Error al obtener datos de usuario: ', error)
        })

    let url = BASE_PATH + '/negocio/logica/agenda.php'
    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.error) {
                // Maneja el mensaje de errors
                $('td[id^="cupos-"]').text(response.error);
            } else {
                // Asignar los cupos a cada día
                $('#cupos-lunes').text(response.Lunes + ' disponibles');
                $('#cupos-martes').text(response.Martes + ' disponibles');
                $('#cupos-miercoles').text(response.Miércoles + ' disponibles');
                $('#cupos-jueves').text(response.Jueves + ' disponibles');
                $('#cupos-viernes').text(response.Viernes + ' disponibles');
                $('#cupos-sabado').text(response.Sábado + ' disponibles');
                $('#cupos-domingo').text(response.Domingo + ' disponibles');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar:', status, error);
            $('td[id^="cupos-"]').text('Error al cargar: ' + error);
        }
    });

    tieneRolCliente && $('#btnGuardar').removeAttr('hidden');

    tieneRolCliente && $("#btnGuardar").click(function(){
        console.log("error?")
        let diasSeleccionados = [];
        $('input[type=checkbox]:checked').each(function(){
            diasSeleccionados.push($(this).val());
        });

        if(diasSeleccionados.length > 0){
            let url = BASE_PATH + '/negocio/logica/reservar.php'

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    dias: diasSeleccionados,
                    //buscar como poner para que quede para todos los codigoPersona
                    codigoPersona: codigoPersona
                },
                dataType: 'json',
                success: function(response){
                    if(response.success){
                        alert(response.message);
                    }else{
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error){
                    console.error('Error al hacer la reserva: ', status, error);
                    //debug
                    console.log(xhr.responseText);
                    alert('Error al reservar');
                }
            })
        }else{
            alert('Por favor, seleccione uno o más días');
        }
    });
};