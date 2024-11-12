$(document).ready(function() {

    // Iniciar el timer con la hora obtenida
    iniciarTimers();
    fetchNewOrders();
    var hora = null;

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
                            '<div class="row mb-2">'+
                                '<div class="card">'+
                                    '<div class="card-body">'+
                                        '<p class="card-title">No. Pedido: ' + order.id_pedido + '</p>'+
                                        '<p class="card-text">No. Mesa: ' + order.num_mesa + '</p>'+
                                        '<p class="card-text">Hora: <span class="timer" data-hora="' + horaServidor + '"></span></p>'+
                                        '<button id="verOrden" class="btn btn-primary" data-id="' + order.id_pedido + '" data-bs-toggle="modal" data-bs-target="#detalleModal"">Ver Orden Completa</button>'+
                                        '<button id="cambiarEsP" class="btn btn-success" data-id="' + order.id_pedido + '-Nuevo">Cambiar Estado</button>'+
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
            dataType: 'JSON',
            success: function(response) {
                var productsTable = $('#productsTable').DataTable();
                productsTable.clear();
                response.forEach(function(producto) {
                    productsTable.row.add([
                        producto.nombre,
                        producto.cantidad
                    ]).draw();
                    
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //showMessage('danger', "Error al cargar productos: " + textStatus + " - " + errorThrown);
            }
        });
    });

    $(document).on('click', '#cambiarEsP', function() {
        var datax = $(this).data('id');
        var a = null, b = null, c = null;
        let [indicator1, indicator2] = datax.split('-');
        a = indicator1;
        b = indicator2;
        alert(a + ' ' + b);
        if(b==='Nuevo') c = 'Preparando';
        else if(b==='Preparando') c = 'Listo';
        else if (b==='Listo') c = 'Despachado';
        else if(b==='Despachado') c = 'Completado';
        var data = { id : a , estado : c };
        $.ajax({
            url: '../controllers/PedidoCocina.php',
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response) {
                cargarCambio();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //
            }
        });
    });

    cargarCambio();

    function cargarCambio() {
        hora = horaServidor;
        var etiqueta = '';
        $.ajax({
            url: '../controllers/PedidoCocina.php?estado',
            type: 'GET',
            dataType: 'JSON',
            success: function(response) {
                limpiarTablas();
                // Verifica si hay datos y los recorre para mostrar los nuevos pedidos
                if (Array.isArray(response)) {
                    response.forEach(function(order) {
                        if(order.estado==='Preparando') etiqueta='#preparando';
                        else if(order.estado==='Listo') etiqueta='#listo';
                        else if(order.estado==='Despachado') etiqueta='#despachado';
                        else if(order.estado==='Nuevo') etiqueta = null;
                        $(etiqueta).append(
                            '<div class="row mb-2">'+
                                '<div class="card">'+
                                    '<div class="card-body">'+
                                        '<p class="card-title">No. Pedido: ' + order.id_pedido + '</p>'+
                                        '<p class="card-text">No. Mesa: ' + order.num_mesa + '</p>'+
                                        '<p class="card-text">Hora: <span class="timer" data-hora="' + hora + '"></span></p>'+
                                        '<button id="verOrden" class="btn btn-primary" data-id="' + order.id_pedido + '" data-bs-toggle="modal" data-bs-target="#detalleModal"">Ver Orden Completa</button>'+
                                        '<button id="cambiarEsP" class="btn btn-success" data-id="' + order.id_pedido + "-" + order.estado + '">Cambiar Estado</button>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'
                        );
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Cancer");
                //showMessage('danger', "Error al cargar productos: " + textStatus + " - " + errorThrown);
            }
        });
    }

    // Inicializar DataTable para productos
    $('#productsTable').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
        }
    });

    function limpiarTablas(){
        $('#preparando').empty();
        $('#listo').empty();
        $('#despachado').empty();
    }

    // Refresca cada 5 segundos
    setInterval(fetchNewOrders, 5000);

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
