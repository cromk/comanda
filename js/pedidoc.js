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
                                        '<button id="verOrden" class="btn btn-primary" data-id="' + order.id_pedido + ' data-bs-toggle="modal" data-bs-target="#productModal"">Ver Orden Completa</button>'+
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
        $.ajax({
            url: '../controllers/PedidoCocina.php?id_pedido='+id,
            type: 'GET',
            success: function(response) {
                console.log(response);
                var productsTable = $('#productsTable').DataTable();
                productsTable.clear();
                response.forEach(function(producto) {
                    productsTable.row.add([
                        producto.nombre,
                        producto.cantidad
                    ]).draw();
                    console.log(producto.nombre);
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
