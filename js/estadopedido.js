$(document).ready(function () {

    //Funcion para cargar los pedidos existentes
    function cargarPedidos() {
        $.ajax({
            url: '../controllers/EstadoController.php?pedidos=true',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                console.log(response);
                var pedidoTabla = $('#tablePedidos').DataTable();
                pedidoTabla.clear(); // Limpia las filas
                response.forEach(function (pedido) {                    
                    pedidoTabla.row.add([
                        pedido.id_pedido,
                        pedido.fecha_pedido,
                        pedido.estado,
                        pedido.num_mesa,
                        pedido.mesero,
                        '<button type="button" id="updatePedido" class="btn bg-gradient-info" data-id="' + pedido.id_pedido + '" data-bs-toggle="modal" data-bs-target="#detalleModal"">Modificar</button>',
                        '<button type="button" id="cancelarPedido" class="btn bg-gradient-danger" data-id="' + pedido.id_pedido + '">Cancelar</button>'
                    ]).draw();
                }); 
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error al cargar los pedidos:", textStatus, errorThrown);
            }
        });
    }

    // Cargar datos en la tabla
    cargarPedidos();

    // Inicializa DataTables
    $('#tablePedidos').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
        }
    });

    $(document).on('click', '#updatePedido', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '../controllers/EstadoController.php?id_pedido='+id,
            type: 'GET',
            dataType: 'JSON',
            success: function(response) {
                console.log(response);
                var productsTable = $('#productsTable').DataTable();
                productsTable.clear();
                response.forEach(function(producto) {
                    productsTable.row.add([
                        producto.nombre,
                        producto.cantidad,
                        ''
                    ]).draw();
                    
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //showMessage('danger', "Error al cargar productos: " + textStatus + " - " + errorThrown);
            }
        });
    });

});
