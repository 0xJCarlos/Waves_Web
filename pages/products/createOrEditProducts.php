<?php 
session_start();
if (isset($_SESSION["user"])){
  if($_SESSION["privilege"] != 1){
    header("Location: ../../index.php");
  }
}

require "../database.php";

if($conn->connect_error){
  die("Falló la conexión: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
  // Fetch product data for editing
  $productID = $_GET['id'];
  $sql = "SELECT * FROM products WHERE id = '$productID'";
  $result = $conn->query($sql);
  $datosProducto = $result->fetch_assoc();

      $nombre = $datosProducto['name'];
      $descripcion = $datosProducto['description'];
      $precio = $datosProducto['price'];
      $categoria = $datosProducto['categoria'];
      $tipo = $datosProducto['tipo'];
      $nombreImagen = $datosProducto['image'];

  }
 else {
  // Initialize product data for creating
  $nombre = '';
  $descripcion = '';
  $precio = '';
  $categoria = '';
  $tipo = '';
  $nombreImagen = '';
}
?>
<!doctype html>
<html lang="en" data-bs-theme="auto">
  <head><script src="color-modes.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.118.2">
    <title>Waves - Modificar Producto</title>
    <link rel="stylesheet" href="../../pages/login/login.css">
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
      #redirect-text{
        text-align: center;
      }

      .form-floating{
        padding-bottom: 5px;
        
      }

      
  </style>
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
  <?php 

      

    if (isset($_POST["submit"])){
      $nombre = $_POST["nombreProducto"];
      $descripcion = $_POST["descripcionProducto"];
      $precio = $_POST["precioProducto"];
      $categoria = $_POST["categoriaProducto"];
      $tipo = $_POST["tipoProducto"];

      $nombreImagen = $_FILES["imagenProducto"]["name"];
      $tipoImagen = $_FILES["imagenProducto"]["type"];
      $tamImagen = $_FILES["imagenProducto"]["size"];


      if($tamImagen < 5000000){
        if($tipoImagen == "image/jpeg" || $tipoImagen == "image/png" || $tipoImagen == "image/gif"){
          //Ruta
          $carpeta = "../../assets/images/";

          //Mover la imagen a la carpeta destino.
          copy($_FILES['imagenProducto']['tmp_name'],$carpeta.$nombreImagen);
          //move_uploaded_file($_FILES["imagenProducto"]["tmp_name"],$carpeta.$nombreImagen);

          //Conectar a BD
          require_once "../database.php";
          
          if (!$conn){
            die("Conexión fallida: " . mysqli_connect_error());
          }

          if(isset($_GET['id'])){
            $IDProducto = $_GET['id'];
            "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
            $sql = "UPDATE products SET name = '$nombre', image = '$nombreImagen', description = '$descripcion', price = '$precio', categoria = '$categoria', tipo = '$tipo' WHERE id = '$IDProducto'";
            if (mysqli_query($conn, $sql)){
              echo "<div class='alert alert-success'>Actualización exitosa.</div>";
            }
            else {
              echo "<div class='alert alert-danger'>". $sql . "<br>" . mysqli_error($conn) . "</div>";
            }
            

          }
          else{
            $sql = "INSERT INTO products (name, image, description, price, categoria, tipo) VALUES ('$nombre', '$nombreImagen', '$descripcion', $precio, '$categoria', '$tipo')";
            if (mysqli_query($conn, $sql)){
              echo "<div class='alert alert-success'>Registro exitoso.</div>";
            }
            else {
              echo "<div class='alert alert-danger'>". $sql . "<br>" . mysqli_error($conn) . "</div>";
            }
          }


        }
        else{
          echo "<div class='alert alert-danger'>Tipo de archivo incorrecto.</div>";
        }
      }
      else{
        echo "<div class='alert alert-danger'>El tamaño de la imagen es mayor a 5MB.</div>";
      }
    }

    
  
  ?>
  

<main class="form-signin w-100 m-auto">
  <?php 
    if(isset($_GET['id'])){
      echo '<form action="createOrEditProducts.php?id='.$_GET['id'].'" method="post" enctype="multipart/form-data">';
    }
      echo '<form action="createOrEditProducts.php" method="post" enctype="multipart/form-data">';
  ?>
        <a href="../../index.php"><img class="mb-4" src="../../assets/logos/minilogo.png" alt="" width="300" height="200"></a>
        <h1 class="h3 mb-3 fw-normal">Crear/Editar Producto</h1>
        <div class="mb-3">
            <label for="formFile" class="form-label">Agregar una imagen.</label>
            <input class="form-control" type="file" id="formFile" name="imagenProducto" size=20 value="">
        </div>
        <div class="form-floating">
        <input type="text" class="form-control" id="floatingInput" placeholder="nombreProducto" name="nombreProducto" value="<?php echo $nombre; ?>">
        <label for="floatingInput">Nombre del Producto</label>
        </div>
        <div class="form-floating">
        <textarea class="form-control" placeholder="Descripción del producto" id="descripcionProducto" name="descripcionProducto"><?php echo $descripcion;?></textarea>
        <label for="descripcionProducto">Descripción del producto</label>
        </div>
        <div class="form-floating">
        <input type="number" class="form-control" id="floatingInput" placeholder="Precio" name="precioProducto" value="<?php echo $precio; ?>">
        <label for="Precio">Precio</label>
        </div>
        <div class="form-floating">
        <input type="text" class="form-control" id="floatingInput" placeholder="categoriaProducto" name="categoriaProducto" value="<?php echo $categoria; ?>">
        <label for="floatingInput">Categoria del Producto</label>
        </div>
        <div class="form-floating">
        <label class="visually-hidden" for="autoSizingSelect">Tipo del Producto</label>
        <select class="form-select" id="autoSizingSelect" name="tipoProducto">
          <option selected>Selecciona el tipo...</option>
          <option value="Disco" value="Disco" <?php echo $tipo == 'Disco' ? 'selected' : ''; ?>>Disco</option>
          <option value="Accesorio" value="Accesorio" <?php echo $tipo == 'Accesorio' ? 'selected' : ''; ?>>Accesorio</option>        
        </select>
        <br>
        <?php 
          if (isset($_GET['id'])){
            echo ' <button class="btn btn-primary w-100 py-2" type="submit" name="submit">Actualizar producto</button>';
          }
          else{
            echo '<button class="btn btn-primary w-100 py-2" type="submit" name="submit">Crear producto</button>';
          }
        ?>
        <p class="mt-5 mb-3 text-body-secondary" id="redirect-text">
            &copy;2023
        </p>
    </form>
</main>
<script src="../../bootstrap.bundle.min.js"></script>
</body>
</html>
