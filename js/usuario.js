$(document).ready(function() {

    //Variables que tendran el id a seleccionar de la tabla
    var deleteUsuarioId = null;
    var editUsuarioId = null;
    var estado = true;

    //Expresion regular para el manejo de contraseña
    const pattern = new RegExp("^(?=.*[A-Z])(?=.*[a-z])(?=.*\\d)(?=.*[.@$!%*?&;+\\-*/])[^\\s]{8,25}$");

    // Función para cargar tipos de usuarios en el select
    function loadTiposUsuario() {
        $.ajax({
            url: '../controllers/UsuarioController.php?tipo_usuario=true',
            type: 'GET',
            success: function(response) {
                var select = $('#u_tipo_usuario');
                select.empty(); // Limpiar el select
                response.forEach(function(tipo) {
                    select.append('<option value="' + tipo.id_tipo_usuario + '">' + tipo.tipo_usuario + '</option>');
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showMessage('danger', "Error al cargar tipos de usuarios: " + textStatus + " - " + errorThrown);
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

    // Leer todos los usuario
    function readAllUsuarios() {
        var btn = "";
        var est = null;
        $.ajax({
            url: '../controllers/UsuarioController.php',
            type: 'GET',
            success: function(response) {
                // Aquí podrías actualizar tu tabla o lista con los datos recibidos
                $('#body-t').empty();
                response.forEach(function(usuario) {
                    if(usuario.estado_usuario)
                    {
                        est = "Habilitado";
                        btn = '<td class="text-center"><button class="btn bg-gradient-danger rw-20 mb-0 toast-btn deleteButton" data-id="'+ usuario.id_usuario + '-' + usuario.estado_usuario + '">Deshabilitar</button></td>';
                    }
                    else
                    {
                        est = "Deshabilitado";
                        btn = '<td class="text-center"><button class="btn bg-gradient-warning rw-20 mb-0 toast-btn deleteButton" data-id="'+ usuario.id_usuario + '-' + usuario.estado_usuario + '">Habilitar</button></td>';
                    }
                    $('#body-t').append(
                        '<tr><td class="text-center"><p class="text-xs font-weight-bold mb-0">'+ usuario.id_usuario + '</p></td>'+
                        '<td class="text-center"><p class="text-xs font-weight-bold mb-0">' + usuario.tipo_usuario + '</p></td>'+
                        '<td class="text-center"><p class="text-xs font-weight-bold mb-0">' + usuario.nombre_usuario + '</p></td>'+
                        '<td class="text-center"><p class="text-xs font-weight-bold mb-0">' + usuario.apellido_usuario + '</p></td>'+
                        '<td class="text-center"><p class="text-xs font-weight-bold mb-0">' + usuario.mail + '</p></td>'+
                        '<td class="text-center"><p class="text-xs font-weight-bold mb-0">' + usuario.fecha_creacion + '</p></td>'+
                        '<td class="text-center"><p class="text-xs font-weight-bold mb-0">' + est + '</p></td>'+
                        btn +
                        '<td class="text-center"><button class="btn bg-gradient-info mb-0 toast-btn editButton" data-id="' + usuario.id_usuario + '">Modificar</button></td></tr>');
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showMessage('danger', "Error en la solicitud: " + textStatus + " - " + errorThrown);
            }
        });
    }

    // Crear nuevo usuario
    $('#actionUsuarioButton').click(function() {
        var id_tipo_usuario = $('#u_tipo_usuario').val();
        var nombre_usuario = $('#nombre_usuario').val();
        var apellido_usuario = $('#apellido_usuario').val();
        var mail = $('#mail').val();
        var password = $('#upassword').val();
        var confcontrasenia = $('#confcontrasenia').val();

        if (!pattern.test(password)) {
            // Contraseña invalida
            showMessage('warning', "Error, la contraseña no cumple con alguno de los requisitos /n Almenos una mayúscula, una minúscula, un numero, un carácter especial, que no tenga espacios");
        } else {
            if (password != confcontrasenia) {
                showMessage('danger', 'Las contraseñas no coinciden');
                return;
            }

            if (!nombre_usuario || !apellido_usuario || !mail || !password) {
                showMessage('danger', "Todos los campos son obligatorios");
                return;
            }

            var url = '../controllers/UsuarioController.php';
            var method = 'POST';
            var data = { id_tipo_usuario: id_tipo_usuario, nombre_usuario: nombre_usuario, apellido_usuario: apellido_usuario, mail: mail, password: password } ;

            if (editUsuarioId !== null) {
                method = 'PUT';
                data.id = editUsuarioId;
            }

            $.ajax({
                url: url,
                type: method,
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response) {
                    showMessage('success', response.message);
                    resetForm();
                    $('#actionUsuarioButton').text('Guardar');
                    editUsuarioId = null;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    showMessage('danger', "Error en la solicitud: " + textStatus + " - " + errorThrown);
                }
            }); 
        }
    });

    // Abrir modal de confirmación de eliminación
    $('#usuarioTable').on('click', '.deleteButton', function() {
        dataId = $(this).data('id');
        let [indicator1, indicator2] = dataId.split('-');
        deleteUsuarioId = indicator1;
        estado = indicator2;
        $('#confirmDeleteModal').modal('show');
    });

    // Abrir formulario para edición
    $('#usuarioTable').on('click', '.editButton', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '../controllers/UsuarioController.php?id=' + id,
            type: 'GET',
            success: function(response) {
                $('#u_tipo_usuario').val(response.id_tipo_usuario);
                $('#nombre_usuario').val(response.nombre_usuario);
                $('#apellido_usuario').val(response.apellido_usuario);
                $('#mail').val(response.mail);
                $('#upassword').val(response.password);
                editUsuarioId = id;
                alert(editUsuarioId);
                $('#actionTipoUsuarioButton').text('Modificar');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showMessage('danger', "Error en la solicitud: " + textStatus + " - " + errorThrown);
            }
        });
    });

    // Confirmar eliminación
    $('#confirmDeleteButton').click(function() {
        var v = false;
        if(estado) v = 0; else v = 1;
        if (deleteUsuarioId !== null) {
            $.ajax({
                url: '../controllers/UsuarioController.php',
                type: 'DELETE',
                contentType: 'application/json',
                data: JSON.stringify({ id: deleteUsuarioId, estado_usuario : v }),
                success: function(response) {
                    showMessage('success', response.message);
                    $('#confirmDeleteModal').modal('hide');
                    resetForm();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    showMessage('danger', "Error en la solicitud: " + textStatus + " - " + errorThrown);
                }
            });
        }
    });

    // Inicializar
    readAllUsuarios();
    loadTiposUsuario(); // Cargar los tipos de usuarios al cargar la página

    function resetForm() {
        readAllUsuarios();
        loadTiposUsuario();
        document.getElementById("nombre_usuario").value = "";
        document.getElementById("apellido_usuario").value = "";
        document.getElementById("mail").value = "";
        document.getElementById("upassword").value = "";
        document.getElementById("confcontrasenia").value = "";
    }
});