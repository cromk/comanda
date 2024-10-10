<?php
  session_start();
  if (!isset($_SESSION['user']) || $_SESSION['tpu'] == 1)
      header("Location: ../views/admin.php");
  elseif (!isset($_SESSION['user']) || $_SESSION['tpu'] == 3) 
    header("Location: ../views/cocinero.php");
  elseif (!isset($_SESSION['user']) || $_SESSION['tpu'] > 3) 
    header("Location: ../login.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Pedido | Mar y Tierra</title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="../css/nucleo-icons.css" rel="stylesheet" />
  <link href="../css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
    <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer src="https://api.nepcha.com/js/nepcha-analytics.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <link id="pagestyle" href="../css/material-dashboard.css" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-200">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" admin.php">
        <img src="../img/2.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white">Dashboard Mesero</span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto " id="sidenav-collapse-main">
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
          <a class="nav-link text-white active bg-gradient-new" href="pedidom.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">receipt</i>
            </div>
            <span class="nav-link-text ms-1">Pedidos</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="facturam.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">money</i>
            </div>
            <span class="nav-link-text ms-1">Facturación</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Otros</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="mesas.php">
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
          <ul class="navbar-nav justify-content-end">
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
    <!--INICIO DE ZONA PROGRAMABLE-->
    <div class="container-fluid py-4">
      <div class="row mb-4">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-new shadow-new border-radius-lg pt-2 pb-1">
                <h6 class="text-white text-capitalize ps-3">SELECCIÓN DE MESA</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="mensaje margen-form"></div>
              <form role="form" class="margen-form" id="GuardarForm">
                <div class="mb-2 col-12">
                  <div class="form-inline">
                    <select name="mesas" id="mesas" class="form-control border"></select>
                    <button type="button" id="selectMesa" class="btn bg-gradient-info toast-btn">Seleccionar</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-new shadow-new border-radius-lg pt-2 pb-1">
                <h6 class="text-white text-capitalize ps-3">DETALLE DEL PEDIDO</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div id="btns" class="d-grid gap-2 d-md-flex justify-content-md-end margen-form">
              </div>
              <div class="d-grid gap-2 d-md-flex justify-content-md margen-form">
                ID Mesero: <input type="text" name="id_mesero" id="id_mesero" disabled value="<?php echo $_SESSION['user_id']; ?>">
              </div>
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0" id="tipoUsuarioTable">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">#</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">PRODUCTO</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">CANTIDAD</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PRECIO UNITARIO</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">VENTAS GRABADAS</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" colspan="2">ACCIONES</th>
                    </tr>
                  </thead>
                  <tbody id="body-t"><!-- CARGA DE TABLA --></tbody>
                </table>
              </div>
              <div id="tdatos" class="d-grid gap-2 d-md-flex justify-content-md-end margen-form"></div>
              <div id="fin" class="d-grid gap-2 d-md-flex justify-content-md-end margen-form"></div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer py-4 ">
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

   <!-- Modal de Productos -->
  <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="productModalLabel">Seleccionar Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table id="productTable" class="display" style="width:100%">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody> <!-- Cargar productos vía AJAX --></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!--FIN DE ZONA PROGRAMABLE-->
  <!-- Core JS Files -->
  <script src="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
  <script src="../js/core/popper.min.js"></script>
  <script src="../js/core/bootstrap.min.js"></script>
  <script src="../js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../js/plugins/smooth-scrollbar.min.js"></script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../js/material-dashboard.min.js?v=3.1.0"></script>
  <script>
    // Asegúrate de que 'id_mesero' esté escapado correctamente para JavaScript
    //var id_mesero = (<?php echo $id; ?> != '') ? <?php echo $id; ?> : null;

  </script>


  <script src="../js/pedidom.js"></script>
</body>

</html>
