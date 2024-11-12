<?php
  session_start();
  if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
  } elseif ($_SESSION['tpu'] == 1) {
      header("Location: ../views/admin.php");
  } elseif ($_SESSION['tpu'] == 2) {
      header("Location: ../views/mesero.php");
  }
  // Obtener la hora del servidor
  date_default_timezone_set('America/El_Salvador'); // Ajusta la zona horaria
  $hora_servidor = date('H:i:s');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
  <title>Tablero de pedidos | Mar y Tierra</title>
  <script> var horaServidor = "<?php echo $hora_servidor; ?>"; </script>
</head>

<body class="g-sidenav-show  bg-gray-200">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" cocinero.php">
        <img src="../img/2.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white">Perfil Cocinero</span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white" href="cocinero.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">Inicio</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Principal</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white active bg-gradient-new" href="pedidoc.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">search</i>
            </div>
            <span class="nav-link-text ms-1">Visualizar pedidos</span>
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
      <div class="row">
        <div class="col-3">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-new shadow-new border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">NUEVO</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="mensaje my-4 margen-form" id="nuevo"></div>
            </div>
          </div>
        </div>
        <div class="col-3">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-new shadow-new border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">PREPARANDO</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="mensaje my-4 margen-form" id="preparando"></div>
            </div>
          </div>
        </div>
        <div class="col-3">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-new shadow-new border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">LISTO PARA ENTREGAR</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="mensaje my-4 margen-form" id="listo"></div>
            </div>
          </div>
        </div>
        <div class="col-3">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-new shadow-new border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">DESPACHADO</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="mensaje my-4 margen-form" id="despachado"></div>              
            </div>
          </div>
        </div>
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
  <!-- Modal de Productos -->
  <!-- Modal de Productos -->
  <div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detalleModalLabel">Detalle del Pedido</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table id="productsTable" class="display" style="width:100%">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Cantidad</th>
              </tr>
            </thead>
            <tbody> <!-- Cargar productos vía AJAX --> </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
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
  <script src="../js/pedidoc.js"></script>
</body>

</html>