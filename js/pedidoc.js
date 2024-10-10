/*$(document).ready(function() {

    // Iniciar el timer con la hora obtenida
    iniciarTimer(horaServidor);

    function fetchNewOrders() {
        $.ajax({
            url: '../controllers/PedidoCocina.php',
            type: 'GET',
            success: function(data) {
                // Limpiar el área de nuevos pedidos
                $('#nuevo').empty();

                // Verifica si hay datos y los recorre para mostrar los nuevos pedidos
                if (Array.isArray(data)) {
                    data.forEach(function(order) {
                        $('#nuevo').append(
                            '<div class="row">'+
                                '<div class="card">'+
                                    '<div class="card-body">'+
                                        '<p class="card-title">No. Mesa: ' + order.id_pedido + '</p>'+
                                        '<p class="card-text">Comentario: ' + order.num_mesa + '</p>'+
                                        '<p class="card-text">Hora: <span id="timer"></span></p>'+
                                        '<button id="verOrden" class="btn btn-primary" data-id="' + order.id_pedido + '">Ver Orden Completa</button>'+
                                        '<button id="cambiarEs" class="btn btn-primary" data-id="' + order.id_pedido + '">Cambiar Estado</button>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'
                        );
                    });
                } else if (data.id_pedido) {
                    $('#nuevo').append(
                        '<div class="row">'+
                            '<div class="card">'+
                                '<div class="card-body">'+
                                    '<h6 class="card-title">No. Mesa: ' + order.id_pedido + '</h6>'+
                                    '<p class="card-text">Comentario: ' + order.num_mesa + '</p>'+
                                    '<p class="card-text">Hora: <span id="timer"></span></p>'+
                                    '<button id="verOrden" class="btn btn-primary" data-id="' + order.id_pedido + '">Ver Orden Completa</button>'+
                                    '<button id="cambiarEs" class="btn btn-primary" data-id="' + order.id_pedido + '">Cambiar Estado</button>'+
                                '</div>'+
                            '</div>'+
                        '</div>'
                    );
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al obtener los nuevos pedidos:', error);
            }
        });
    }

    /*$(document).on('click', '#cambiarEs', function() {
        var id_pedido = $(this).data('id'); // Obtiene el ID del producto
        var data = { id_pedido : id_pedido, estado = 'Preparación'}; // Datos para la solicitud
        $.ajax({
            url: '../controllers/PedidoCocina.php',
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response) {
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showMessage('danger', "Error en la solicitud: " + textStatus + " - " + errorThrown);
            }
        });
    });*/
/*
    $(document).on('click', '#verOrden', function() {
        $.ajax({// Realiza una solicitud AJAX para obtener los productos disponibles
            url: '../controllers/PedidoCocina.php?pedido=true',
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

    // Refresca cada 5 segundos
    setInterval(fetchNewOrders, 5000);

    // Función para agregar ceros si el valor es menor a 10
    function padZero(value) {
        return value < 10 ? '0' + value : value;
    }
            
    // Función para iniciar el timer
    function iniciarTimer(horaServidor) {
        let partesHora = horaServidor.split(':');
        let horas = parseInt(partesHora[0]);
        let minutos = parseInt(partesHora[1]);
        let segundos = parseInt(partesHora[2]);

        // Función que actualiza el timer cada segundo
        setInterval(function() {
            segundos++;
            if (segundos >= 60) {
                segundos = 0;
                minutos++;
            }

            if (minutos >= 60) {
                minutos = 0;
                horas++;
            }

            if (horas >= 24) {
                horas = 0;
            }

            // Actualizar el DOM
                $('#timer').text(padZero(horas) + ':' + padZero(minutos) + ':' + padZero(segundos));
        }, 1000);
    }
});
*/
//----------------------------------------------------------------------
$(document).ready(function() {

    // Iniciar el timer con la hora obtenida
    iniciarTimers();
    fetchNewOrders();

    function fetchNewOrders() {
        $.ajax({
            url: '../controllers/PedidoCocina.php',
            type: 'GET',
            success: function(data) {
                // Limpiar el área de nuevos pedidos
                $('#nuevo').empty();

                // Verifica si hay datos y los recorre para mostrar los nuevos pedidos
                if (Array.isArray(data)) {
                    data.forEach(function(order) {
                        $('#nuevo').append(
                            '<div class="row">'+
                                '<div class="card">'+
                                    '<div class="card-body">'+
                                        '<p class="card-title">No. Pedido: ' + order.id_pedido + '</p>'+
                                        '<p class="card-text">No. Mesa: ' + order.num_mesa + '</p>'+
                                        '<p class="card-text">Hora: <span class="timer" data-hora="' + horaServidor + '"></span></p>'+
                                        '<button id="verOrden" class="btn btn-primary" data-id="' + order.id_pedido + '">Ver Orden Completa</button>'+
                                        '<button id="cambiarEs" class="btn btn-primary" data-id="' + order.id_pedido + '">Cambiar Estado</button>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'
                        );
                    });
                }

                // Iniciar los timers para todos los nuevos pedidos
                iniciarTimers();
            },
            error: function(xhr, status, error) {
                console.error('Error al obtener los nuevos pedidos:', error);
            }
        });
    }

    $(document).on('click', '#verOrden', function() {
        var id = $(this).data('id');
        alert("PRUEBA" + id);
        $.ajax({
            url: '../controllers/PedidoCocina.php?id_pedido='+id,
            type: 'GET',
            success: function(response) {
                var productTable = $('#productsTable').DataTable();
                productTable.clear();
                response.forEach(function(producto) {
                    productTable.row.add([
                        producto.nombre,
                        producto.precio,
                        '<input type="number" class="form-control" id="cantidad_' + producto.id_item + '" min="1" value="1">',
                        '<button type="button" class="btn bg-gradient-info addProduct" data-id="' + producto.id_item + '" data-nombre="' + producto.nombre + '" data-precio="' + producto.precio + '">Agregar</button>'
                    ]).draw();
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showMessage('danger', "Error al cargar productos: " + textStatus + " - " + errorThrown);
            }
        });
    });

    // Inicializar DataTable para productos
    $('#productsTable').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
        }
    });

    // Refresca cada 5 segundos
    //setInterval(fetchNewOrders, 5000);

    // Función para agregar ceros si el valor es menor a 10
    function padZero(value) {
        return value < 10 ? '0' + value : value;
    }
    
    // Función para iniciar los timers en cada tarjeta
    function iniciarTimers() {
        $('.timer').each(function() {
            let timerElement = $(this);
            let horaServidor = timerElement.data('hora');
            let partesHora = horaServidor.split(':');
            let horas = parseInt(partesHora[0]);
            let minutos = parseInt(partesHora[1]);
            let segundos = parseInt(partesHora[2]);

            setInterval(function() {
                segundos++;
                if (segundos >= 60) {
                    segundos = 0;
                    minutos++;
                }

                if (minutos >= 60) {
                    minutos = 0;
                    horas++;
                }

                if (horas >= 24) {
                    horas = 0;
                }

                // Actualizar el elemento específico del timer
                timerElement.text(padZero(horas) + ':' + padZero(minutos) + ':' + padZero(segundos));
            }, 1000);
        });
    }
});
