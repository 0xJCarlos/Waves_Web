<?php 
session_start();

if (isset($_SESSION["user"])){
    if($_SESSION["privilege"] != 1){
      header("Location: ../../index.php");
    }
}
require_once "../database.php";


$sql = "SELECT id, name, image, description, price FROM products";
$products = $conn->query($sql);

$rutaBase = "../../assets/images/";
//$urlBase = "http://" . $_SERVER['HTTP_HOST'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
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
<br><br><br>
<div class="container">
      <h1>Dashboard de productos</h1>
      <a href="createOrEditProducts.php" class="btn btn-primary mb-3">Agregar un nuevo producto</a>
      <div class="row">
        <?php foreach ($products as $product):
              $urlImagen =  $rutaBase . $product["image"];
        ?>
          <div class="col-md-4">
            <div class="card">
              <img src="<?= $urlImagen ?>" class="card-img-top" alt="<?= $product['name'] ?>">
              <div class="card-body">
                <h5 class="card-title"><?= $product['name'] ?></h5>
                <p class="card-text"><?= $product['description'] ?></p>
                <p class="card-text"><?= $product['price'] ?></p>
                <a href="createOrEditProducts.php?id=<?= $product['id'] ?>" class="btn btn-primary">Edit</a>
                <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn btn-danger">Delete</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

</body>
</html>