$(document).ready(function() {

    var deleteProductoId = null;
    var editProductoId = null;
    var estado = null;

    function loadCategorias() {
        $.ajax({
            url: '../controllers/ProductoController.php?categoria=true',
            type: 'GET',
            success: function(response) {
                var select = $('#id_categoria');
                select.empty(); // Limpiar el select
                response.forEach(function(categoria) {
                    select.append('<option value="' + categoria.id_categoria + '">' + categoria.nombre_categoria + '</option>');
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showMessage('danger', "Error al cargar las categorias del producto: " + textStatus + " - " + errorThrown);
            }
        });
    }

    // Mostrar mensajes en el alert
    function showMessage(type, message) {
        $('.mensaje').html(
            '<div class="alert alert-' + type + ' alert-dismissible text-white fade show" role="alert"><span class="text-sm">'
            + message +'<button type="button" class="btn-close text-lg py-3 opacity-10" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
        );
    }

    // Leer todos los productos
    function readAllProductos() {
        var btn = "";
        var est = null;
        $.ajax({
            url: '../controllers/ProductoController.php',
            type: 'GET',
            dataType: 'JSON',  // Especifica que esperas una respuesta JSON
            success: function(response) {
                $('#body-t').empty();
                response.forEach(function(menuitem) {
                    if(menuitem.estado === 'D')
                    {
                        est = "Disponible";
                        btn = '<td class="text-center"><button class="btn bg-gradient-danger rw-20 mb-0 toast-btn deleteButton" data-id="'+ menuitem.id_item + '-' + menuitem.estado + '">Deshabilitar</button></td>';
                    }
                    else
                    {
                        est = "Agotado";
                        btn = '<td class="text-center"><button class="btn bg-gradient-warning rw-20 mb-0 toast-btn deleteButton" data-id="'+ menuitem.id_item + '-' + menuitem.estado + '">Habilitar</button></td>';
                    }
                    $('#body-t').append(
                        '<tr><td class="text-center"><p class="text-xs font-weight-bold mb-0">'+ menuitem.id_item + '</p></td>'+
                        '<td class="text-start"><p class="text-xs font-weight-bold mb-0">'+ menuitem.nombre_categoria + '</p></td>'+
                        '<td class="text-start"><p class="text-xs font-weight-bold mb-0">'+ menuitem.nombre + '</p></td>'+
                        '<td class="text-start"><p class="text-xs font-weight-bold mb-0">'+ menuitem.descripcion + '</p></td>'+
                        '<td class="text-center"><p class="text-xs font-weight-bold mb-0">'+ menuitem.precio + '</p></td>'+
                        '<td class="text-center"><p class="text-xs font-weight-bold mb-0">'+ est + '</p></td>'+
                        '<td class="text-center"><img src="' + menuitem.foto + '" alt="Producto" width="50"></td>'+
                        btn +
                        '<td><button class="btn bg-gradient-info mb-0 toast-btn editButton" data-id="' + menuitem.id_item + '">Modificar</button></td></tr>');
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR + ' - ' + textStatus + ' - ' + errorThrown);
                showMessage('danger', "Error en la solicitud: " + textStatus + " - " + errorThrown);
            }
        });
    }

    // Crear o modificar productos
    $('#actionProductoButton').click(function() {
        var formData = new FormData();
        formData.append('nombre', $('#nombre').val());
        formData.append('descripcion', $('#descripcion').val());
        formData.append('precio', $('#precio').val());
        formData.append('id_categoria', $('#id_categoria').val());
        formData.append('estado', $('#estado').val());
        formData.append('foto_actual', $('#foto_actual').val());

        var fotoFile = $('#foto')[0].files[0];
        if (fotoFile) {
            formData.append('foto', fotoFile);
        }

        if (!$('#nombre').val() || !$('#descripcion').val() || !$('#precio').val() || !$('#id_categoria').val() || !$('#estado')) {
            showMessage('danger', "Complete los campos requeridos");
            return;
        }

        if (editProductoId !== null) {
            formData.append('_method', 'PUT'); // Campo oculto para simular PUT
            formData.append('id', editProductoId);
        }

        $.ajax({
            url: '../controllers/ProductoController.php',
            type: 'POST', // Usamos POST para ambas operaciones
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                showMessage('success', response.message);
                readAllProductos();
                $('#GuardarForm')[0].reset();
                $('#actionProductoButton').text('Guardar');
                editProductoId = null;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showMessage('danger', "Error en la solicitud: " + textStatus + " - " + errorThrown);
            }
        });
    });

    // Abrir modal de confirmación de eliminación
    $('#productoTable').on('click', '.deleteButton', function() {
        dataId = $(this).data('id');
        let [indicator1, indicator2] = dataId.split('-');
        deleteProductoId = indicator1;
        estado = indicator2;
        $('#confirmDeleteModal').modal('show');
    });

    $('#productoTable').on('click', '.editButton', function() {
        var id = $(this).data('id');

        $.ajax({
            url: '../controllers/ProductoController.php?id=' + id,
            type: 'GET',
            success: function(response) {
                $('#nombre').val(response.nombre);
                $('#descripcion').val(response.descripcion);
                $('#precio').val(response.precio);
                $('#id_categoria').val(response.id_categoria);
                $('#estado').val(response.estado);
                $('#foto_actual').val(response.foto);
                $('#actionProductoButton').text('Modificar');
                editProductoId = id;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showMessage('danger', "Error en la solicitud: " + textStatus + " - " + errorThrown);
            }
        });
    });

    // Confirmar eliminación
    $('#confirmDeleteButton').click(function() {
        if (estado==='D') { estado = 'A'; }
        else { estado = 'D'; }
        if (deleteProductoId !== null) {
            $.ajax({
                url: '../controllers/ProductoController.php',
                type: 'DELETE',
                contentType: 'application/json',
                data: JSON.stringify({ id: deleteProductoId , estado : estado}),
                success: function(response) {
                    showMessage('success', response.message);
                    $('#confirmDeleteModal').modal('hide');
                    readAllProductos();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    showMessage('danger', "Error en la solicitud: " + textStatus + " - " + errorThrown);
                }
            });
        }
    });

    // Inicializar
    readAllProductos();
    loadCategorias();

    function resetForm() {
        document.getElementById("tipo_usuario").value = "";
        document.getElementById("descripcion_tipo_usuario").value = "";
    }
});