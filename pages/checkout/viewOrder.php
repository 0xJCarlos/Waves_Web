<?php 
session_start();

if (isset($_SESSION["detalleEnvio"])){
    $primerNombre = $_SESSION["detalleEnvio"]["primerNombre"];
    $apellido = $_SESSION["detalleEnvio"]["apellido"];
    $correoEnvio = $_SESSION["detalleEnvio"]["correoEnvio"];
    $direccionEnvio = $_SESSION["detalleEnvio"]["direccionEnvio"];
    $direccionEnvio2 = $_SESSION["detalleEnvio"]["direccionEnvio2"];
    $pais = $_SESSION["detalleEnvio"]["pais"];
    $estado = $_SESSION["detalleEnvio"]["estado"];
    $codigoPostal = $_SESSION["detalleEnvio"]["codigoPostal"];


}

if (isset($_SESSION["cart"])){
    $nombreCC = $_SESSION["detallePago"]["nombreCC"];
    $numCC = $_SESSION["detallePago"]["numCC"];
    $ccExp = $_SESSION["detallePago"]["ccExp"];
    $ccCVV = $_SESSION["detallePago"]["ccCVV"];
        
    }

if (isset($_POST["terminarOrden"])){
    require '../database.php';
    if(isset($_SESSION["Id"])){
        $userID = $_SESSION["Id"];
    }
    $direccionCompleta = $direccionEnvio . " , " . $direccionEnvio2;
    //Insertar a addresses
    $sql = "INSERT INTO addresses (user_id, first_name, last_name, email, street , state, country, postal_code) VALUES (?,?,?,?,?,?,?,?)";
    $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
    if ($prepareStmt){
        mysqli_stmt_bind_param($stmt,"isssssss",$userID, $primerNombre, $apellido, $correoEnvio, $direccionCompleta, $estado, $pais, $codigoPostal);
        mysqli_stmt_execute($stmt);
        $shipping_address_id = mysqli_insert_id($conn); // Get the ID of the newly inserted address
        echo "<div class='alert alert-success'>Registro de datos de envío exitoso</div>";
    }
    else{
        die("Algo salió mal insertando datos de envío");
    }

    //Insertar a ordenes


    $sql = "INSERT INTO orders (user_id, shipping_address_id, total_amount, order_date) VALUES (?,?,?, CURRENT_TIMESTAMP)";
    $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
    if ($prepareStmt){
        mysqli_stmt_bind_param($stmt,"iid",$userID, $shipping_address_id, $_SESSION["totalAPagar"]);
        mysqli_stmt_execute($stmt);
        $orderID = mysqli_insert_id($conn); // Get the ID of the newly inserted address
        echo "<div class='alert alert-success'>Registro de orden exitoso</div>";
    }
    else{
        die("Algo salió mal insertando datos de la orden.");
    }

    //Insertar detalles de ordenes
    foreach($_SESSION["cart"] as $key => $item){
        $totalPorProducto = ($item["priceProducto"] * $item["cantidad"]);
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price_paid) VALUES (?,?,?,?)";
        $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
        if ($prepareStmt){
            mysqli_stmt_bind_param($stmt,"iiii", $orderID, $item["idProducto"], $item["cantidad"], $totalPorProducto);
            mysqli_stmt_execute($stmt);
            echo "<div class='alert alert-success'>Registro de productos en la orden exitoso.</div>";
        }
        else{
            die("Error: " . mysqli_error($conn));
        }
    }


    unset($_SESSION["cart"]);
    header("Location: viewOrders.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Waves</title>
    <link rel="stylesheet" href="../../bootstrap.min.css">

    <style>
        #tarjetaProducto{
            margin-bottom: 5px;
        }

    </style>
</head>
<body>
    <body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="../../index.php">Waves</a>
            <form class="d-flex" role="search" action="../products/products.php" method="get">
                <input class="form-control me-2" type="search" placeholder="Buscar productos..." aria-label="Search" name="busqueda">
                <button class="btn btn-outline-success" type="submit">Buscar</button>
            </form>
            <div>
                <?php 
                  if (isset($_SESSION["user"])){
                    if ($_SESSION["privilege"] == 1){
                      echo '<a href="../products/productDashboard.php"><img src="../../assets/icons/database.svg" alt="Dashboard de productos"  width="30" height="24" class="d-inline-block align-text-top"></a>';
                    }
                  }
                ?>
                <a href="../login/admin-login.php"><img src="../../assets/icons/person-circle.svg" alt="Perfil"  width="30" height="24" class="d-inline-block align-text-top"></a>
                <a href="../checkout/viewOrders.php"><img src="../../assets/icons/bag-heart.svg" alt="Mis ordenes"  width="30" height="24" class="d-inline-block align-text-top"></a>
                <a href="../checkout/viewCart.php"><img src="../../assets/icons/cart2.svg" alt="Perfil"  width="30" height="24" class="d-inline-block align-text-top"><?php if (isset($_SESSION["cart"]) AND count($_SESSION["cart"])  > 0 ) {echo  count($_SESSION["cart"]);}?></a>
                
            </div>
        </div>
    </nav>
    <div class="container py-5">
        <h2 class="mb-5">Revisa tu Pedido</h2>
        <div class="row g-5">
            <div class="col-sm-3">
                <h4>Detalles de envío</h4>
                <p><strong>First Name:</strong> <?php echo $primerNombre; ?></p>
                <p><strong>Last Name:</strong> <?php echo $apellido; ?></p>
                <p><strong>Email:</strong> <?php echo $correoEnvio; ?></p>
                <p><strong>Address:</strong> <?php echo $direccionEnvio; ?></p>
                <p><strong>Address 2:</strong> <?php echo $direccionEnvio2; ?></p>
                <p><strong>Country:</strong> <?php echo $pais; ?></p>
                <p><strong>State:</strong> <?php echo $estado; ?></p>
                <p><strong>Postal Code:</strong> <?php echo $codigoPostal; ?></p>
            </div>
            <div class="col-sm-3">
                <h4>Detalles de pago</h4>
                <p><strong>Name on Card:</strong> <?php echo $nombreCC; ?></p>
                <p><strong>Card Number:</strong> <?php echo $numCC; ?></p>
                <p><strong>Expiry:</strong> <?php echo $ccExp; ?></p>
                <p><strong>CVV:</strong> <?php echo $ccCVV; ?></p>
            </div>

      <div class="col-md-6">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-primary">Tu carrito</span>
          <span class="badge bg-primary rounded-pill"><?php if(isset($_SESSION["cart"])){echo count($_SESSION["cart"]); }  ?></span>
        </h4>
        <ul class="list-group mb-3">
            <?php 
                if(isset($_SESSION["cart"])){
                    
                    foreach ($_SESSION["cart"] as $key=> $item){
                        $precioTotal = ($item["priceProducto"] * $item["cantidad"]);
                        echo '
                        <form method="POST">
                            <li class="list-group-item d-flex justify-content-between lh-sm" id="tarjetaProducto">
                                <div>
                                    <h6 class="my-0">Nombre del Producto</h6>
                                    <h7 class="text-body-secondary">'. $item["nameProducto"].'</h7>
                                </div>
                                <div>
                                    <h6 class="my-0">Precio Unitario</h6>
                                    <h7 class="text-body-secondary">$'. $item["priceProducto"].'</h7>
                                </div>
                                <div>
                                    <h6 class="my-0">Unidades</h6>
                                    <h7 class="text-body-secondary">'. $item["cantidad"].'</h7>

                                </div>
                                <div>
                                    <h6 class="my-0">Precio Total</h6>
                                    <h7 class="my-0"><b>$'. $precioTotal .'</b> </h7>
                                </div>
                            </li>
                        </form>
                        ';
                    }
                }
            ?>
            <li class="list-group-item d-flex justify-content-between">
                <h6 class="my-0">Total a Pagar</h6>
                    <strong>$
                        <?php 
                        if(isset($_SESSION["cart"])){
                            $precioTotal = 0;
                            foreach ($_SESSION["cart"] as $key=> $item){
                                $precioPorProducto = $item["priceProducto"]*$item["cantidad"];
                                $precioTotal += $precioPorProducto;
                            }
                            echo $precioTotal;
                            $_SESSION["totalAPagar"] = $precioTotal;
                        }
                        ?>
                    </strong>
          </li>
        </div>
    <div class="mb-5">
        <form action="viewOrder.php" method="post">
            <button class="w-100 btn btn-primary btn-lg" type="submit" name="terminarOrden">Terminar Orden</button>
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</body>
</html>