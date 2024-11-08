$(document).ready(function() {
    // Cargar pedidos despachados al cargar la página
    cargarPedidosDespachados();
  
    // Función para cargar pedidos despachados
    function cargarPedidosDespachados() {
      $.ajax({
        url: '../controllers/PedidoController.php?pedido=true',
        type: 'GET',
        success: function(response) {
          if (response.status === 'success') {
            const select = $('#pedidos');
            select.empty();
            response.data.forEach(pedido => {
              select.append(`<option value="${pedido.id}">Pedido: ${pedido.id} | Mesa: ${pedido.num_mesa}</option>`);
            });
          } else {
            alert("No se encontraron pedidos despachados.");
          }
        },
        error: function() {
          alert("Error al cargar los pedidos despachados.");
        }
      });
    }
  
    // Obtener detalles del pedido seleccionado
    $('#selectPedido').click(function() {
      const pedidoId = $('#pedidos').val();
      if (!pedidoId) {
        alert("Por favor, selecciona un pedido despachado.");
        return;
      }
      $.ajax({
        url: `../controllers/PedidoController.php?pedido=true&id=${pedidoId}`,
        type: 'GET',
        success: function(response) {
          if (response.status === 'success') {
            mostrarDetallesPedido(response.data);
          } else {
            alert("Error al obtener los detalles del pedido.");
          }
        },
        error: function() {
          alert("Error en la solicitud para obtener el pedido.");
        }
      });
    });
  
    // Función para mostrar detalles del pedido en la tabla
    function mostrarDetallesPedido(data) {
      const tbody = $('#body-t');
      tbody.empty();
      data.lineas_pedido.forEach((item, index) => {
        tbody.append(`
          <tr>
            <td class="text-center">${index + 1}</td>
            <td>${item.nombre}</td>
            <td class="text-center">${item.cantidad}</td>
            <td class="text-center">${item.precio_unitario}</td>
            <td class="text-center">${(item.cantidad * item.precio_unitario).toFixed(2)}</td>
          </tr>
        `);
      });
    }
  
    // Generar y descargar la factura en PDF
    $('#imprimirFactura').click(function() {
      const pedidoId = $('#pedidos').val();
      if (!pedidoId) {
        alert("Por favor, selecciona un pedido antes de imprimir.");
        return;
      }
  
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
  
      // Encabezado de la factura
      doc.setFontSize(16);
      doc.text("Factura", 105, 10, { align: "center" });
      doc.setFontSize(12);
      doc.text(`ID Pedido: ${pedidoId}`, 10, 30);
      doc.text(`Fecha: ${new Date().toLocaleDateString()}`, 10, 40);
  
      // Tabla de productos
      let yPosition = 50;
      doc.text("Producto", 10, yPosition);
      doc.text("Cantidad", 80, yPosition);
      doc.text("Precio Unitario", 130, yPosition);
      doc.text("Total", 180, yPosition);
      yPosition += 10;
  
      $('#body-t tr').each(function() {
        const nombre = $(this).find('td:eq(1)').text();
        const cantidad = $(this).find('td:eq(2)').text();
        const precioUnitario = $(this).find('td:eq(3)').text();
        const total = $(this).find('td:eq(4)').text();
  
        doc.text(nombre, 10, yPosition);
        doc.text(cantidad, 80, yPosition);
        doc.text(precioUnitario, 130, yPosition);
        doc.text(total, 180, yPosition);
        yPosition += 10;
      });
  
      // Descargar el PDF
      doc.save(`Factura_Pedido_${pedidoId}.pdf`);
    });
  });
  