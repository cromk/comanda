$(document).ready(function() {
    //Variable que contrala el numero de mesa seleccionada
    var mesa = null;
    var id_mesero = null;

    // VARIABLE ASIGNADA PARA EL PEDIDO COMO OBJETO
    const pedido = {
        estado: "Nuevo",
        num_mesa: null,
        id_mesero: null,
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
     * Carga las mesas disponibles mediante una solicitud AJAX al servidor.
     * Actualiza el contenido del elemento select con id 'mesas'.
     */
    function cargarMesasDisponibles(){
        console.log("CARGANDO MESAS");
        $.ajax({// Realiza una solicitud AJAX para obtener las mesas disponibles
            url: '../controllers/PedidoController.php?mesa=true',
            type: 'GET',
            success: function(response) {
                console.log(response);
                var select = $('#mesas');
                select.empty(); // Limpia el select antes de agregar nuevas opciones
                if(response.length===0)
                    select.append('<option>Sin mesas disponibles</option>');
                else {
                    response.forEach(function(mesa) {
                        select.append('<option value="' + mesa.num_mesa + '">Num. Mesa: ' + mesa.num_mesa + '&emsp;|&emsp;Máx. Personas: ' + mesa.cant_personas + '</option>');
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
                showMessage('danger', "Error al cargar mesas disponibles: " + textStatus + " - " + errorThrown);
            }
        });
    }

    cargarMesasDisponibles();// Carga las mesas disponibles al inicializar la página

     // Maneja el evento click en el botón de selección de mesa
    $('#selectMesa').click(function() {
        mesa = $('#mesas').val();// Obtiene el valor de la mesa seleccionada
        id_mesero = $('#id_mesero').val(); //Obtiene el codigo del mesero
        var data = { num_mesa : mesa, estado : 'O'};// Datos para la solicitud
        $.ajax({// Realiza una solicitud AJAX para actualizar el estado de la mesa
            url: '../controllers/PedidoController.php',
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response) {
                cargarMesasDisponibles();// Recarga las mesas disponibles
                $('#selectMesa').prop('disabled', true);//Desabilita el boton de seleccionar mesa
                //Se agrega el boton para modificar mesa
                $('#btns').append('<button type="button" id="changeMesa" class="btn bg-gradient-warning toast-btn">CAMBIAR DE MESA</button>');
                //Se agrega boton para agregar nuevo producto al pedido
                $('#btns').append('<button type="button" id="newProduct" class="btn bg-gradient-info toast-btn" data-bs-toggle="modal" data-bs-target="#productModal">+ PRODUCTO</button>');
                $('#tdatos').empty();//Se limpia la tabla de productos
                //Se agrega boton para enviar pedido a cocina
                $('#fin').append('<div class="mb-2"><button type="button" id="realizarPedido" class="btn bg-gradient-success toast-btn">REALIZAR PEDIDO</button></div>');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showMessage('danger', "Error en la solicitud: " + textStatus + " - " + errorThrown);
            }
        });
    });

    // Maneja el evento click en el botón de cambio de mesa
    $(document).on('click', '#changeMesa', function() {
        var data = { num_mesa : mesa, estado : 'D'}; // Datos para la solicitud
        $.ajax({
            url: '../controllers/PedidoController.php',
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response) {
                cargarMesasDisponibles();// Recarga las mesas disponibles
                $('#selectMesa').prop('disabled', false);// Habilita el botón de selección de mesa
                $('#fin').empty();//Elimina los elementos agregados en el div fin
                $('#btns').empty();//Elimina los botones agregados en el div btns
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showMessage('danger', "Error en la solicitud: " + textStatus + " - " + errorThrown);
            }
        });
    });

    // Carga los productos en el modal cuando se hace clic en el botón de nuevo producto
    $(document).on('click', '#newProduct', function() {
        $.ajax({// Realiza una solicitud AJAX para obtener los productos disponibles
            url: '../controllers/PedidoController.php?productos=true',
            type: 'GET',
            success: function(response) {
                var productTable = $('#productTable').DataTable();// Obtiene la instancia de DataTable
                productTable.clear();// Limpia la tabla de productos
                response.forEach(function(producto) {
                    productTable.row.add([
                        producto.nombre,
                        producto.precio,
                        '<input type="number" class="form-control" id="cantidad_' + producto.id_item + '" min="1" value="1">',
                        '<button type="button" class="btn bg-gradient-info addProduct" data-id="' + producto.id_item + '" data-nombre="' + producto.nombre + '" data-precio="' + producto.precio + '">Agregar</button>'
                    ]).draw();// Agrega una nueva fila a la tabla de productos
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showMessage('danger', "Error al cargar productos: " + textStatus + " - " + errorThrown);
            }
        });
    });

    // Maneja el evento click para agregar un producto a la tabla de pedidos
    $(document).on('click', '.addProduct', function() {
        var id_item = $(this).data('id'); // Obtiene el ID del producto
        var nombre = $(this).data('nombre'); // Obtiene el nombre del producto
        var precio = $(this).data('precio'); // Obtiene el precio del producto
        var cantidad = $('#cantidad_' + id_item).val(); // Obtiene la cantidad seleccionada

        // Crea una nueva fila para la tabla de pedidos
        var nuevaFila = '<tr>' +
            '<td class="text-center">' + id_item + '</td>' +
            '<td>' + nombre + '</td>' +
            '<td class="text-center"> <input type="number" class="form-control cantidad-input text-center" value="' + cantidad + '" min="1"> </td>' +
            '<td class="text-center">' + precio + '</td>' +
            '<td class="text-center">' + (cantidad * precio) + '</td>' +
            '<td class="text-center"><button type="button" class="btn btn-danger removeProduct">Eliminar</button></td>' +
            '</tr>';

        $('#body-t').append(nuevaFila); // Agrega la nueva fila a la tabla de pedidos
        $('#productModal').modal('hide'); // Cierra el modal de productos
    });

    // Eliminar producto de la tabla de pedidos
    $(document).on('click', '.removeProduct', function() {
        $(this).closest('tr').remove();//Elimina la fina seleccionada
    });

    $(document).on('click', '#realizarPedido', () => {
        pedido.num_mesa = mesa;
        pedido.id_mesero = id_mesero;
        pedido.lineas_pedido = []; // Inicializa el array para evitar acumulaciones
    
        $('#body-t tr').each(function() {
            var id_item = $(this).find('td:eq(0)').text();
            var cantidad = $(this).find('.cantidad-input').val(); // Obtener la cantidad del input
            var precio_unitario = $(this).find('td:eq(3)').text();
        
            // Agregar los datos al array de objetos
            pedido.lineas_pedido.push({
                id_pedido: null,  // Este valor se asignará al realizar el pedido
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
            url: '../controllers/PedidoController.php', 
            type: 'POST',
            contentType: 'application/json',
            data: data,
            success: function(response) {
                if (response.status === 'success') {
                    $('#body-t').empty();//Se limpia la tabla de productos
                    $('#selectMesa').prop('disabled', false);//Desabilita el boton de seleccionar mesa
                    $('#fin').empty();//Elimina los elementos agregados en el div fin
                    $('#btns').empty();//Elimina los botones agregados en el div btns
                    showMessage('success','Pedido registrado con exito');
                    cargarMesasDisponibles();
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
                showMessage('danger', "Error al guardar pedido: " + textStatus + " - " + errorThrown);
            }
        });
    });
    
    

    // Inicializar DataTable para productos
    $('#productTable').DataTable({
        "language": {// Configura el lenguaje de la DataTable en español
            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
        }
    });


    /* Inicializar DataTable para productos pero con archivo local
    $('#productTable').DataTable({
        "language": {// Configura el lenguaje de la DataTable en español
            "url": "http://localhost/comanda/i18n/es_es.json"
        }
    });*/
});
