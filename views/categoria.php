<?php
  session_start();
  if (!isset($_SESSION['user']) || $_SESSION['tpu'] == 2)
      header("Location: ../views/mesero.php");
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
  <title>Categorias del Menú | Mar y Tierra</title>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <link href="../css/nucleo-icons.css" rel="stylesheet" />
  <link href="../css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <link id="pagestyle" href="../css/material-dashboard.css" rel="stylesheet" />
  <script defer src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <!-- Custom JS -->
  <script src="../js/categoria.js"></script>
</head>

<body class="g-sidenav-show  bg-gray-200">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" admin.php">
        <img src="../img/2.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white">Perfil Admin</span>
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
            <span class="nav-link-text ms-1">Inicio</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Soporte y Mantenimiento</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="rol.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">engineering</i>
            </div>
            <span class="nav-link-text ms-1">Rol de Usuarios</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="usuario.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">person</i>
            </div>
            <span class="nav-link-text ms-1">Usuarios</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white active bg-gradient-new" href="categoria.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">liquor</i>
            </div>
            <span class="nav-link-text ms-1">Categorias del Menú</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="producto.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">restaurant</i>
            </div>
            <span class="nav-link-text ms-1">Producto del Menú</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="mesa.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">apps</i>
            </div>
            <span class="nav-link-text ms-1">Mesas</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="pedido.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">receipt</i>
            </div>
            <span class="nav-link-text ms-1">Pedidos</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="factura.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">money</i>
            </div>
            <span class="nav-link-text ms-1">Facturación</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Otros</h6>
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
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-new shadow-new border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">AGREGAR CATEGORIA DE MENÚ</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="mensaje my-4 margen-form"></div>
              <form role="form" class="margen-form" id="GuardarForm">
                <div class="mb-2">
                  <label class="form-label" for="nomcategoria">Nombre de la Categoria:</label>
                  <input type="text" class="form-control border" id="nomcategoria" required>
                </div>
                <div class="input-group input-group-outline mb-2">
                  <button type="button" id="actionCategoriaButton" class="btn bg-gradient-success w-20 mb-0 toast-btn">Guardar</button>
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
              <div class="bg-gradient-new shadow-new border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">LISTADO DE CATEGORIAS DEL MENÚ</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0" id="categoriaTable">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">NOMBRE CATEGORIA</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" colspan="2">ACCIONES</th>
                    </tr>
                  </thead>
                  <tbody id="body-t"><!-- CARGA DE TABLA --></tbody>
                </table>
              </div>
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
  <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea eliminar esta categoria?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../js/material-dashboard.min.js"></script>
</body>

</html>