<?php 
session_start();

if (isset($_SESSION["cart"])){
  $cantidadEnCarrito = count($_SESSION["cart"]);
}
else{
  $cantidadEnCarrito = 0;
}

if (isset($_POST["busqueda"])){
  $_SESSION["productosPorMostrar"] = $_POST["busqueda"];
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap.min.css" rel="stylesheet">
    

    <style>
        .flex-equal > * {
    flex: 1;
    }
    @media (min-width: 768px) {
    .flex-md-equal > * {
        flex: 1;
    }
    }

    .Discos{
        color:
        text-decoration: none;
    }

    .Accesorios{
        color: #fff;
        text-decoration: none;
    }

    .Discos{
        color: black;
        text-decoration: none;
    }
    
    </style>

    <title>Waves</title>
    
</head>
<body>



    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Waves</a>
            <form class="d-flex" role="search" action="pages/products/products.php" method="get">
                <input class="form-control me-2" type="search" placeholder="Buscar productos..." aria-label="Search" name="busqueda">
                <button class="btn btn-outline-success" type="submit">Buscar</button>
            </form>
            <div>
                <?php 
                  if (isset($_SESSION["user"])){
                    if ($_SESSION["privilege"] == 1){
                      echo '<a href="pages/products/productDashboard.php"><img src="assets/icons/database.svg" alt="Dashboard de productos"  width="30" height="24" class="d-inline-block align-text-top"></a>';
                    }
                  }
                ?>
                <a href="pages/login/admin-login.php"><img src="assets/icons/person-circle.svg" alt="Perfil"  width="30" height="24" class="d-inline-block align-text-top"></a>
                <a href="pages/checkout/viewOrders.php"><img src="assets/icons/bag-heart.svg" alt="Mis ordenes"  width="30" height="24" class="d-inline-block align-text-top"></a>
                <a href="pages/checkout/viewCart.php"><img src="assets/icons/cart2.svg" alt="Perfil"  width="30" height="24" class="d-inline-block align-text-top"><?php if (isset($cantidadEnCarrito) AND $cantidadEnCarrito  > 0 ) {echo $cantidadEnCarrito;}?></a>
            </div>
        </div>
    </nav>
    <main>
  <div class="d-md-flex flex-md-equal w-100 my-md-3 ps-md-3">
  <a href="pages/products/products.php?tipo=Disco" class="Discos" name="Discos">
    <?php
      $_SESSION["productosPorMostrar"] = "Disco";
    ?>
    <div class="bg-body-tertiary me-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
      <div class="my-3 p-3">
        <h2 class="display-5">Discos</h2>
        <p class="lead">And an even wittier subheading.</p>
      </div>
      <div class="bg-dark shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;"></div>
    </div>
    </a>
    <a href="pages/products/products.php?tipo=Accesorio" class="Accesorios">
      <?php 
        $_SESSION["productosPorMostrar"] = "Disco";
      ?>
    <div class="text-bg-primary me-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
      <div class="my-3 py-3">
        <h2 class="display-5">Accesorios</h2>
        <p class="lead">And an even wittier subheading.</p>
      </div>
      <div class="bg-body-tertiary shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;"></div>
    </div>
    </a>
  </div>
</main>
<footer class="container py-5">
  <div class="row">
    <div class="col-12 col-md">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="d-block mb-2" role="img" viewBox="0 0 24 24"><title>Product</title><circle cx="12" cy="12" r="10"/><path d="M14.31 8l5.74 9.94M9.69 8h11.48M7.38 12l5.74-9.94M9.69 16L3.95 6.06M14.31 16H2.83m13.79-4l-5.74 9.94"/></svg>
      <small class="d-block mb-3 text-body-secondary">&copy; 2017–2023</small>
    </div>
    <div class="col-6 col-md">
      <h5>Features</h5>
      <ul class="list-unstyled text-small">
        <li><a class="link-secondary text-decoration-none" href="#">Cool stuff</a></li>
        <li><a class="link-secondary text-decoration-none" href="#">Random feature</a></li>
      </ul>
    </div>
    <div class="col-6 col-md">
      <h5>Resources</h5>
      <ul class="list-unstyled text-small">
        <li><a class="link-secondary text-decoration-none" href="#">Resource name</a></li>
        <li><a class="link-secondary text-decoration-none" href="#">Resource</a></li>
      </ul>
    </div>
    <div class="col-6 col-md">
      <h5>Resources</h5>
      <ul class="list-unstyled text-small">
        <li><a class="link-secondary text-decoration-none" href="#">Business</a></li>
        <li><a class="link-secondary text-decoration-none" href="#">Education</a></li>
      </ul>
    </div>
    <div class="col-6 col-md">
      <h5>About</h5>
      <ul class="list-unstyled text-small">
        <li><a class="link-secondary text-decoration-none" href="#">Team</a></li>
        <li><a class="link-secondary text-decoration-none" href="#">Locations</a></li>
      </ul>
    </div>
  </div>
</footer>

    </body>
    <script src=bootstrap.bundle.min.js"></script>
</body>
</html>