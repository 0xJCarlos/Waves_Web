<?php
session_start();
?>
<!DOCTYPE html>
<html lang="sp">
<head>
    
    <title>Waves- Ordenes</title>
    <link rel="stylesheet" href="../../bootstrap.min.css">
</head>
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
    <br><br><br>
    <div class="py-5 text-center">
        <a href="../../index.php"><img class="d-block mx-auto mb-4" src="../../assets/logos/minilogo.png" alt="" width="72" height="57"></a>
        <h2>Tus ordenes</h2>
        <?php 
        if (!isset($_SESSION["cart"])){
            echo '<p class="lead">No tienes productos en tu carrito.</p>';
            echo '<p class="lead"> <a href="../../index.php">Visita la tienda</a></p>';
      }
      ?>
    </div>
    <?php
    
    require '../database.php';

    if(isset($_SESSION["Id"])){
        $userID = $_SESSION["Id"];

        $sql = "SELECT orders.order_id, orders.total_amount, orders.order_date, addresses.first_name, addresses.last_name, addresses.email, addresses.street, addresses.state, addresses.country, addresses.postal_code, order_items.product_id, order_items.quantity, order_items.price_paid, products.Id, products.name
        FROM orders
        INNER JOIN addresses ON orders.shipping_address_id = addresses.address_id
        INNER JOIN order_items ON orders.order_id = order_items.order_id
        INNER JOIN products ON order_items.product_id = products.Id
        WHERE orders.user_id = $userID
        ORDER BY orders.order_id DESC";

        $result = mysqli_query($conn, $sql);

        if (!$result) {
            printf("Error: %s\n", mysqli_error($conn));
            exit();
        }

        while($row = $result->fetch_assoc()) {
            echo "<div class='container my-3'>";
                echo "<div class='card'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>ID de la Orden: " . $row['order_id'] . "</h5>";
                    echo "<p class='card-text'>Nombre del Cliente: " . $row['first_name'] . " " . $row['last_name'] . "</p>";
                    echo "<p class='card-text'>Email: " . $row['email'] . "</p>";
                    echo "<p class='card-text'>Nombre del producto: " . $row['name'] . "</p>";
                    echo "<p class='card-text'>Cantidad: " . $row['quantity'] . "</p>";
                    echo "<p class='card-text'>Precio Unitario: " . $row['price_paid'] . "</p>";
                    echo "<p class='card-text'>Precio Total: " . $row['total_amount'] . "</p>";
                    echo "<p class='card-text'>Dirección: " . $row['street'] . ", " . $row['state'] . ", " . $row['country'] . ", " . $row['postal_code'] . "</p>";
                    $timestamp = strtotime($row['order_date']);
                    $formattedDate = date('Y-m-d H:i:s', $timestamp);
                    echo "<p class='card-text'>Fecha de orden: " . $formattedDate = date('l jS \of F Y h:i:s A', $timestamp) . "</p>";
                    
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        }
    
        mysqli_close($conn);
    }
    else{
        echo 'No hay sesión iniciada';
    }




    ?>
</body>
</html>
