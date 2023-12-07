<?php 
session_start();

if(isset($_SESSION["cart"])){
  $cantidadEnCarrito = count($_SESSION["cart"]);
}

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
}



?>
<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="auto">
  <head>
    <script src="../assets/js/color-modes.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.118.2">
    <title>Producto - Waves</title>
<link href="../../bootstrap.min.css" rel="stylesheet"> 
</head>
<body>
<?php 
$rutaBase = "/practicas/proyecto_final/assets/images/";
$urlBase = "http://" . $_SERVER['HTTP_HOST'];

require "../database.php";

if ($conn->connect_error){
  die("Falló la conexión: " . $conn->connect_error);
}

$idProducto = $_GET['Id'];

if ($idProducto > 0){
    $sql = "SELECT Id, name, image, description, price, categoria, tipo FROM products WHERE Id='$idProducto'";
    $result = $conn->query($sql);
}
else{
    echo "No se encontró el producto";
}
?>

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
<div class="container">
        <?php 
        $producto = $result->fetch_assoc();
        $urlImagen = $urlBase . $rutaBase . $producto["image"];
        
        echo '
        <form action="productView.php?Id='.$producto["Id"].'" method="POST">
           <div class="row">
          <div class="col-md-6">
              <img src="' . $urlImagen . '" class="card-img-top" alt="Miniatura">
          </div>
          <div class="col-md-6">
              <input name="idProducto" type="hidden" id="idProducto" value="'. $producto["Id"] .'" />
              <input name="cantidad" type="hidden" id="cantidad" value="1" />
              <input name="nameProducto" type="hidden" id="nombre" value="'.  $producto["name"] .'" />
              <h2>'.$producto["name"] .'</h2>
              <p>'. $producto["description"].'</p>
              <input name="priceProducto" type="hidden" id="precio" value="'.  $producto["price"] .'" />
              <h4>$'.$producto["price"].'</h4>
              <button class="btn btn-primary">Añadir al carrito</button>
          </div>
        </form>
        
      </div>
        ';
        ?>

    </div>

</body>
</html>