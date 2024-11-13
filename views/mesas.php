<?php
  session_start();
  if (!isset($_SESSION['user']) || $_SESSION['tpu'] == 1)
      header("Location: ../views/admin.php");
  elseif (!isset($_SESSION['user']) || $_SESSION['tpu'] == 3) 
    header("Location: ../views/cocinero.php");
  elseif (!isset($_SESSION['user']) || $_SESSION['tpu'] > 3) 
    header("Location: ../login.php");
  include("../controllers/ConsultasController.php");
  $mesas = consultarMesas();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Dashbord Mesero | Mar y Tierra</title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
  <link id="pagestyle" href="../css/material-dashboard.css" rel="stylesheet" />
</head>

<body class="g-sidenav-show  bg-gray-200">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" admin.php">
        <img src="../img/2.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white">Dashboard Mesero</span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white" href="admin.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Principal</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="pedidom.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">receipt</i>
            </div>
            <span class="nav-link-text ms-1">Pedidos</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Otros</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white active bg-gradient-new" href="mesas.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">search</i>
            </div>
            <span class="nav-link-text ms-1">Visibilidad de Mesas</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="menu.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">search</i>
            </div>
            <span class="nav-link-text ms-1">Ver Menu</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="estado_pedido.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">search</i>
            </div>
            <span class="nav-link-text ms-1">Estado de pedidos</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
          <ul class="navbar-nav  justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a href="../logout.php" class="nav-link text-body font-weight-bold px-0">
                <i class="fa fa-user me-sm-1"></i>
                <span class="d-sm-inline d-none">Cerrar Sesión</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row mb-4">
        <table id="myTable" class="display border" style="width:100%">
          <thead>
            <tr>
                <th class="text-start">NUM MESA</th>
                <th class="text-start">MÁXIMO DE PERSONAS</th>
                <th class="text-start">ESTADO</th>
                <th>MODIFICAR ESTADO</th>
            </tr>
          </thead>        
          <tfoot>
            <tr>
                <th class="text-start">NUM MESA</th>
                <th class="text-start">MÁXIMO DE PERSONAS</th>
                <th class="text-start">ESTADO</th>
                <th>MODIFICAR ESTADO</th>
            </tr>
          </tfoot>
          <tbody id="cuerpo">
          <?php foreach ($mesas as $m) {
            $id = $m['num_mesa']; $cant = $m['cant_personas']; $estado = $m['estado']; ?>
            <tr>
              <td class="text-center text-xs font-weight-bold mb-0"><?php echo $id; ?></td>
              <td class="text-center text-xs font-weight-bold mb-0"><?php echo $cant; ?></td>
              <td class="text-center text-xs font-weight-bold mb-0"><?php echo $estado; ?></td>
              <td class="text-center"><button class="btn bg-gradient-info mb-0 toast-btn editButton" data-id="<?php echo $id; ?>">MODIFICAR</button></td>
            </tr>
            <?php } ?>
      </tbody>
    </table>
      </div>
      <footer class="footer py-4  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © 2024 | UES
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteModalLabel">Modificar estado de Mesa</h5>
          <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="texto mb-2">
          ¿Está seguro de que desea modificar el estado de esta mesa?
          </div>
          <div>
            <select name="estado" id="estado" class="form-control border">
              <option value="D">Disponible</option>
              <option value="O">Ocupada</option>
              <option value="L">Limpiando</option>
              <option value="M">En mantenimiento</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary cancel" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger update" id="confirmModal">Modificar</button>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
  <script>
    $(document).ready(function() {
    var table = $('#myTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json'
        }
    });

    $('#cuerpo').on('click', '.editButton', function() {
        var id = $(this).data('id');
        $('#confirmModal').data('id', id).modal('show');
    });

    $('#confirmModal').on('click', '.cancel', function() {
        $('#confirmModal').modal('hide');
    });

    $('#confirmModal').on('click', '.update', function() {
        var id = $('#confirmModal').data('id');
        var estado = $('#estado').val();

        // Llamada AJAX para actualizar el estado en el servidor usando PUT y un diferenciador
        $.ajax({
            url: '../controllers/MesaController.php', 
            method: 'PUT',  // Usamos PUT en lugar de PUT2
            contentType: 'application/json',
            data: JSON.stringify({ id: id, estado: estado, updateEstado: true }),  // Diferenciador updateEstado
            success: function(response) {
                location.reload();
                $('#confirmModal').modal('hide');
            },
            error: function(xhr, status, error) {
                // Manejar error
                console.error(error);
            }
        });
    });
});

  </script>
  <script type="text/javascript" src="../js/mesa.js"></script>
</body>
</html>