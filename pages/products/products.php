<?php 
session_start();
if (isset($_GET['tipo'])){
  $productosAMostrar = $_GET['tipo'];
}

elseif(isset($_GET["busqueda"])){
  $productosAMostrar = $_GET["busqueda"];
}

?>


<!doctype html>
<html lang="en" dir="ltr" data-bs-theme="auto">
  <head><script src="../assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.118.2">
    <title>Productos - Waves</title>


<link href="../../bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .card-text{
        text-align: left;
      }

      .tarjetaProducto{
        text-decoration: none;
      }



    </style>

    
  </head>
  <body>
<?php 
$rutaBase = "../../assets/images/";
//$urlBase = "http://" . $_SERVER['HTTP_HOST'];

if(isset($_SESSION["cart"])){
  $cantidadEnCarrito = count($_SESSION["cart"]);
}
require "../database.php";

if ($conn->connect_error){
  die("Falló la conexión: " . $conn->connect_error);
}


//definir la consulta SQL
if(isset($_GET['tipo'])){
  $sql = "SELECT Id, name, image, description, price, categoria, tipo FROM products WHERE tipo='$productosAMostrar'";
  $_SESSION["productosPorMostrar"] = $productosAMostrar;
  $_GET['tipo'] = $productosAMostrar;

}
elseif(isset($_GET['busqueda'])) {
  $sql = "SELECT Id, name, image, description, price, categoria FROM products WHERE name LIKE '%$productosAMostrar%'";
  $_SESSION["productosPorMostrar"] =   $productosAMostrar;
  $_GET['busqueda'] = $productosAMostrar;


}
else{
$sql = "SELECT Id, name, image, description, price, categoria FROM products";
}

$result = $conn->query($sql);

if (isset($_POST["idProducto"], $_POST["nameProducto"], $_POST["priceProducto"], $_POST["cantidad"])){
  $idProducto =  $_POST["idProducto"];
  $nameProducto = $_POST["nameProducto"];
  $priceProducto = $_POST["priceProducto"];
  $cantidad =  $_POST["cantidad"];

  $item = [
    "idProducto" => $idProducto,
    "nameProducto" => $nameProducto,
    "priceProducto" => $priceProducto,
    "cantidad" => $cantidad
  ];

  if(isset($_SESSION["cart"])){
    array_push($_SESSION["cart"], $item);
    $cantidadEnCarrito = count($_SESSION["cart"]);

  }
  else{
    $_SESSION["cart"] = [$item]; // Note the square brackets
    $cantidadEnCarrito = 0;
  }
}
else {
  $idProducto =  "";
  $nameProducto = "";
  $priceProducto = "";
  $cantidad =  "";
  $cantidadEnCarrito = 0;
}

?>


<main>
<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="../../index.php">Waves</a>
            <form class="d-flex" role="search" action="products.php" method="get">
                <input class="form-control me-2" type="search" placeholder="Buscar productos..." aria-label="Search" name="busqueda">
                <button class="btn btn-outline-success" type="submit">Buscar</button>
            </form>
            <div>
                <?php 
                  if (isset($_SESSION["user"])){
                    if ($_SESSION["privilege"] == 1){
                      echo '<a href="productDashboard.php"><img src="../../assets/icons/database.svg" alt="Dashboard de productos"  width="30" height="24" class="d-inline-block align-text-top"></a>';
                    }
                  }
                ?>
                <a href="../login/admin-login.php"><img src="../../assets/icons/person-circle.svg" alt="Perfil"  width="30" height="24" class="d-inline-block align-text-top"></a>
                <a href="../checkout/viewOrders.php"><img src="../../assets/icons/bag-heart.svg" alt="Mis ordenes"  width="30" height="24" class="d-inline-block align-text-top"></a>
                <a href="../checkout/viewCart.php"><img src="../../assets/icons/cart2.svg" alt="Perfil"  width="30" height="24" class="d-inline-block align-text-top"><?php if (isset($_SESSION["cart"]) AND count($_SESSION["cart"])  > 0 ) {echo  count($_SESSION["cart"]);}?></a>
                
            </div>
        </div>
    </nav>
  <section class="py-5 text-center container">
    <div class="row py-lg-5">
      <div class="col-lg-6 col-md-8 mx-auto">
        <?php 
          if(isset($_GET['tipo'])){
            echo '<h1 class="fw-light">'. $_GET['tipo'].'</h1>';
          }
          elseif(isset($_GET['busqueda'])){
            echo '<h1 class="fw-light">Resultados de la busqueda.</h1>';

          }
        ?>
        <p class="lead text-body-secondary">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
      </div>
    </div>
  </section>

  <div class="album py-5 bg-body-tertiary">
    
    <div class="container">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        <?php 
          if ($result->num_rows > 0) {
            // Mostrar los datos de cada fila
            while($row = $result->fetch_assoc()) {
              $urlImagen =  $rutaBase . $row["image"];
              if(isset($_GET['tipo'])){
                echo '<form action="products.php?tipo='.$_GET['tipo'].'" method="POST">';
              }
              elseif(isset($_GET['busqueda'])){
                echo '<form action="products.php?busqueda='.$_GET['busqueda'].'" method="POST">';
              }
              echo'

              <div class="col">
                <a href="productView.php?Id=' . $row["Id"] . '" class="tarjetaProducto">
                <input name="idProducto" type="hidden" id="idProducto" value="'. $row["Id"] .'" />
                <input name="cantidad" type="hidden" id="cantidad" value="1" />

                  <div class="card shadow-sm">
                    <img src="' . $urlImagen . '" class="card-img-top" alt="Miniatura">
                    <div class="card-body">
                      <input name="nameProducto" type="hidden" id="nombre" value="'.  $row["name"] .'" />
                      <p class="card-text">' . $row["name"] . '</p>
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                          <button type="submit" class="btn btn-sm btn-outline-secondary"><img style="max-width: 220px; max-height: 220px" src="../../assets/icons/cart-plus.svg" alt=""></button>
                        </div>
                        <input name="priceProducto" type="hidden" id="precio" value="'.  $row["price"] .'" />
                        <small class="text-body-secondary">$' . $row["price"] . '</small>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
              </form>
              ';
            }
          } else {
            echo "<div class='alert alert-danger'>No se encontraron productos.</div>";
          }
          $conn->close();
        ?>
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
<script src="../../bootstrap.bundle.min.js"></script>
    </body>
</html>
