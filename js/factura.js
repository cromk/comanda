$(document).ready(function() {
    //Variable que contrala el numero de pedido seleccionado
    var pedido = null;
    var id_cajero = null;

    // VARIABLE ASIGNADA PARA EL PEDIDO COMO OBJETO
    const pedido = {
        estado: "Despachado",
        num_pedido: null,
        id_cajero: null,
        lineas_pedido: [],
    }

    // Mostrar mensajes en el alert
    function showMessage(type, message) {
        //Aperturamos el elemento html y agregamos el mensaje
        $('.mensaje').html(
            '<div class="alert alert-' + type + ' alert-dismissible text-white fade show" role="alert"><span class="text-sm">'
            + message +'<button type="button" class="btn-close text-lg py-3 opacity-10" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
        );
    }

    /**
     * Carga los pedidos despachados mediante una solicitud AJAX al servidor.
     * Actualiza el contenido del elemento select con id 'pedidos'.
     */
    function cargarPedidosDespachados(){
        console.log("CARGHANDO PEDIDOS");
        $.ajax({// Realiza una solicitud AJAX para obtener los pedidos despachados
            url: '../controllers/PedidoController.php?pedido=true',
            type: 'GET',
            success: function(response) {
                console.log(response);
                var select = $('#pedidos');
                select.empty(); // Limpia el select antes de agregar nuevas opciones
                if(response.length===0)
                    select.append('<option>Sin pedidos despachados</option>');
                else {
                    response.forEach(function(pedido) {
                        select.append('<option value="' + pedido.id_pedido + '">Num.pedido: ' + pedido.id_pedido + ' &emsp;|&emsp;Num.Mesa: '+ pedido.num_mesa  + '</option>');
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
                showMessage('danger', "Error al cargar Pedido: " + textStatus + " - " + errorThrown);
            }
        });
    }

    cargarPedidosDespachados();// Carga los pedidos despachados al inicializar la página

     // Maneja el evento click en el botón de selección de pedido
    $('#selectPedido').click(function() {
        pedido = $('#pedidos').val();// Obtiene el valor del pedido seleccionado
        id_cajero = $('#id_cajero').val(); //Obtiene el codigo del cajero
        var data = { id_pedido : pedido };// Datos para la solicitud
        $.ajax({// Realiza una solicitud AJAX para mostrar el pedido 
            url: '../controllers/PedidoController.php',
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response) {
                cargarPedidosDespachados();// Recarga los pedidos despachados
                $('#selectPedido').prop('disabled', true);//Desabilita el boton de seleccionar pedido
                //Se agrega el boton para cambiar pedido
                $('#btns').append('<button type="button" id="changePedido" class="btn bg-gradient-warning toast-btn">CAMBIAR DE PEDIDO</button>');
                $('#tdatos').empty();//Se limpia la tabla de productos
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showMessage('danger', "Error en la solicitud: " + textStatus + " - " + errorThrown);
            }
        });
    });

    // Maneja el evento click en el botón de cambio de pedido
    $(document).on('click', '#changePedido', function() {
        var data = { id_pedido : pedido}; // Datos para la solicitud
        $.ajax({
            url: '../controllers/PedidoController.php',
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response) {
                cargarPedidosDespachados();// Recarga los pedidos despachados
                $('#selectPedido').prop('disabled', false);// Habilita el botón de selección de pedido
                $('#fin').empty();//Elimina los elementos agregados en el div fin
                $('#btns').empty();//Elimina los botones agregados en el div btns
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showMessage('danger', "Error en la solicitud: " + textStatus + " - " + errorThrown);
            }
        });
    });


    $(document).on('click', '#imprimirFactura', () => {
        pedido.num_pedido = pedido;
        pedido.id_cajero = id_cajero;
        pedido.lineas_pedido = []; // Inicializa el array para evitar acumulaciones
    
        $('#body-t tr').each(function() {
            var id_item = $(this).find('td:eq(0)').text();
            var cantidad = $(this).find('.cantidad-input').val(); // Obtener la cantidad del input
            var precio_unitario = $(this).find('td:eq(3)').text();
        
            // Agregar los datos al array de objetos
            pedido.lineas_pedido.push({
                id_pedido: null,  // Este valor se asignará al imprimir la factura
                id_item: id_item,
                cantidad: cantidad,
                precio_unitario: precio_unitario
            });
        });
    
        console.log(pedido); // Para verificar los datos antes de enviar
        const data = JSON.stringify(pedido)
        console.log(data);
        // Realizar la solicitud AJAX para crear el pedido
        $.ajax({
            url: '../controllers/PedidoController.php', // Cambia esto a la URL correcta si es necesario
            type: 'POST',
            contentType: 'application/json',
            data: data,
            success: function(response) {
                if (response.status === 'success') {
                    $('#body-t').empty();//Se limpia la tabla de productos
                    $('#selectPedido').prop('disabled', false);//Desabilita el boton de seleccionar pedido
                    $('#fin').empty();//Elimina los elementos agregados en el div fin
                    $('#btns').empty();//Elimina los botones agregados en el div btns
                    showMessage('success','Pedido Impreso con exito');
                    cargarPedidosDespachados();
                } else {
                    // Manejar el error
                    showMessage('warning', "Error al cargar productos: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                // Manejar errores de la solicitud AJAX
                console.error('AJAX error:', status, error);
                console.log('AJAX error:'+ "ESTATUS:"+ status + "ERROR" + error);
                console.log(xhr);
                showMessage('danger', "Error al imprimir factura: " + textStatus + " - " + errorThrown);
            }
        });
    });
    
    

    // Inicializar DataTable para productos
    $('#productTable').DataTable({
        "language": {// Configura el lenguaje de la DataTable en español
            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
        }
    });



});
