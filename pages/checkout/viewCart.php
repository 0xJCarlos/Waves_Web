<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove'])) {
    $key = $_POST['remove'];
    unset($_SESSION["cart"][$key]);
    if(empty($_SESSION["cart"])) {
        unset($_SESSION["cart"]);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['removeAll'])){
        unset($_SESSION["cart"]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateQuantity'])){
    $key = $_POST['updateQuantity'];
    $quantity = $_POST['cantidadDeProducto'];
    $_SESSION["cart"][$key]["cantidad"] = $quantity;
}

if(isset($_SESSION["cart"])){
    $cantidadEnCarrito = count($_SESSION["cart"]);
    $newCart = array();
    foreach ($_SESSION["cart"] as $key=> $item){
        if (array_key_exists($item["idProducto"], $newCart)){
            $newCart[$item["idProducto"]]["cantidad"] += $item["cantidad"];
        }
        else{
            $newCart[$item["idProducto"]] = $item;
        }
    }
    $_SESSION["cart"] = $newCart;
}



$primerNombre = isset($_POST["primerNombre"]) ? $_POST["primerNombre"] : "";
$apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : "";
$correoEnvio = isset($_POST["correoEnvio"]) ? $_POST["correoEnvio"] : "";
$direccionEnvio = isset($_POST["direccionEnvio"]) ? $_POST["direccionEnvio"] : "";
$direccionEnvio2 = isset($_POST["direccionEnvio2"]) ? $_POST["direccionEnvio2"] : "";
$pais = isset($_POST["pais"]) ? $_POST["pais"] : "";
$estado = isset($_POST["estado"]) ? $_POST["estado"] : "";
$codigoPostal = isset($_POST["codigoPostal"]) ? $_POST["codigoPostal"] : "";
$nombreCC = isset($_POST["nombreCC"]) ? $_POST["nombreCC"] : "";
$numCC = isset($_POST["numCC"]) ? $_POST["numCC"] : "";
$ccExp = isset($_POST["ccExp"]) ? $_POST["ccExp"] : "";
$ccCVV = isset($_POST["ccCVV"]) ? $_POST["ccCVV"] : "";

if(isset($_POST["enviarDatos"])){
    $errors = array();
    if (empty($primerNombre) OR empty($apellido) OR empty($correoEnvio) OR empty($direccionEnvio) OR empty($pais) OR empty($estado) OR empty($codigoPostal)){
        array_push($errors, "Uno o varios campos de envío no fueron introducidos");
    }
    if (empty($nombreCC)){
        array_push($errors, "El campo 'nombreCC' no fue introducido.");
    }
    if (empty($numCC)){
        array_push($errors, "El campo 'numCC' no fue introducido.");
    }
    if (empty($ccExp)){
        array_push($errors, "El campo 'ccEXP' no fue introducido.");
    }
    if (empty($ccCVV)){
        array_push($errors, "El campo 'ccCVV' no fue introducido.");
    }
    if (!filter_var($correoEnvio, FILTER_VALIDATE_EMAIL)){
        array_push($errors, "El correo introducido no es valido.");
    }
    
    if(count($errors)>0){
        foreach($errors as $error){
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
    else{
        $detalleEnvio = [
            "primerNombre" => $primerNombre,
            "apellido" => $apellido,
            "correoEnvio" => $correoEnvio,
            "direccionEnvio" => $direccionEnvio,
            "direccionEnvio2" => $direccionEnvio2,
            "pais" => $pais,
            "estado" => $estado,
            "codigoPostal" => $codigoPostal
    ];
    $detallePago = [
            "nombreCC" => $nombreCC,
            "numCC" => $numCC,
            "ccExp" => $ccExp,
            "ccCVV" => $ccCVV
    ];
        $_SESSION["detalleEnvio"] = $detalleEnvio;
        $_SESSION["detallePago"] = $detallePago;
    
        if (isset($_SESSION["detalleEnvio"]) AND isset($_SESSION["detallePago"])){
            header("Location: viewOrder.php");
        }
    }
}



?>

<!doctype html>
<html lang="en" data-bs-theme="auto">
  <head>

    <title>Checkout - Waves</title>

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

      .b-example-divider {
        width: 100%;
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
      }

      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }

      .bi {
        vertical-align: -.125em;
        fill: currentColor;
      }

      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }

      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }

      .btn-bd-primary {
        --bd-violet-bg: #712cf9;
        --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

        --bs-btn-font-weight: 600;
        --bs-btn-color: var(--bs-white);
        --bs-btn-bg: var(--bd-violet-bg);
        --bs-btn-border-color: var(--bd-violet-bg);
        --bs-btn-hover-color: var(--bs-white);
        --bs-btn-hover-bg: #6528e0;
        --bs-btn-hover-border-color: #6528e0;
        --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
        --bs-btn-active-color: var(--bs-btn-hover-color);
        --bs-btn-active-bg: #5a23c8;
        --bs-btn-active-border-color: #5a23c8;
      }

      .bd-mode-toggle {
        z-index: 1500;
      }

      .bd-mode-toggle .dropdown-menu .active .bi {
        display: block !important;
      }

      .textoPrecioProducto{
        -right: 5px;
      }

      

    </style>

    
    <!-- Custom styles for this template -->
    <link href="checkout.css" rel="stylesheet">
  </head>
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
  <body class="bg-body-tertiary">
   

    
<div class="container">
  <main>
    <div class="py-5 text-center">
        <a href="../../index.php"><img class="d-block mx-auto mb-4" src="../../assets/logos/minilogo.png" alt="" width="72" height="57"></a>
      <h2>Checkout form</h2>
      <?php 
      if (!isset($_SESSION["cart"])){
        echo '<p class="lead">No tienes productos en tu carrito.</p>';
        echo '<p class="lead"> <a href="../../index.php">Visita la tienda</a></p>';

      }
      ?>
    </div>

    <div class="row g-5">
      <div class="col-md-5 col-lg-4 order-md-last">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-primary">Your cart</span>
          <span class="badge bg-primary rounded-pill"><?php if(isset($_SESSION["cart"])){echo count($_SESSION["cart"]); }  ?></span>
        </h4>
        <ul class="list-group mb-3">
            <?php 
                if(isset($_SESSION["cart"])){
                    foreach ($_SESSION["cart"] as $key=> $item){
                        echo '
                        <form method="POST">
                            <li class="list-group-item d-flex justify-content-between lh-sm">
                                <div>
                                    <h6 class="my-0">'. $item["nameProducto"].'</h6>
                                </div>
                                <div>
                                <span class="text-body-secondary" id="textoPrecioProducto">$'.$item["priceProducto"].'</span>
                                <div class="btn-group">
                                    <input type="number" value="'.$item["cantidad"].'" min="1" max="100" name="cantidadDeProducto"></input>
                                    <button class="btn btn-sm btn-success btn-outline-secondary" type="submit" name="updateQuantity" value='.$key.'><img id="actualizarCarrito" src="../../assets/icons/arrow-clockwise.svg"></button>
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-danger btn-outline-secondary" id="removerDeCarrito" type="submit" name="remove" value='.$key.'><img id="removerDeCarrito" src="../../assets/icons/cart-dash.svg"></button>
                                </div>
                                </div>
                            </li>
                        </form>
                        ';
                    }
                }
            ?>
          <li class="list-group-item d-flex justify-content-between">
            <span>Total</span>
            <strong>$
                <?php 
                if(isset($_SESSION["cart"])){
                    $precioTotal = 0;
                    foreach ($_SESSION["cart"] as $key=> $item){
                        $precioPorProducto = $item["priceProducto"]*$item["cantidad"];
                        $precioTotal += $precioPorProducto;
                    }
                    echo $precioTotal;
                }
                ?>
            </strong>
          </li>
        </ul>

        <form class="card p-2" method="POST" action="viewCart.php">
          <div class="input-group">
            <button type="submit" name="removeAll" class="btn btn-danger">Vaciar carrito</button>
          </div>
        </form>
      </div>
      <div class="col-md-7 col-lg-8">
        <h4 class="mb-3">Datos de envío</h4>
        <form class="needs-validation"  method="POST" action="viewCart.php"novalidate>
          <div class="row g-3">
            <div class="col-sm-6">
              <label for="firstName" class="form-label">Nombre(s)</label>
              <input type="text" class="form-control" id="firstName" name="primerNombre" placeholder="" value="" required>
              <div class="invalid-feedback">
                Introduce un texto válido.
              </div>
            </div>

            <div class="col-sm-6">
              <label for="lastName" class="form-label">Apellido(s)</label>
              <input type="text" class="form-control" id="lastName" name="apellido" placeholder="" value="" required>
              <div class="invalid-feedback">
                Introduce un texto válido.
              </div>
            </div>

            <div class="col-12">
              <label for="email" class="form-label">Email <span class="text-body-secondary"></span></label>
              <input type="email" class="form-control" id="email" name="correoEnvio" placeholder="you@example.com">
              <div class="invalid-feedback">
                Introduce un correo válido.
              </div>
            </div>

            <div class="col-12">
              <label for="address" class="form-label">Dirección</label>
              <input type="text" class="form-control" id="address" name="direccionEnvio"placeholder="Calle Principal 123, Col. Primera" required>
              <div class="invalid-feedback">
                Introduce tu dirección para la entrega.
              </div>
            </div>

            <div class="col-12">
              <label for="address2" class="form-label">Dirección 2 <span class="text-body-secondary">(Optional)</span></label>
              <input type="text" class="form-control" id="address2" name="direccionEnvio2" placeholder="Edificio Alto, Cuarto 111">
            </div>

            <div class="col-md-5">
              <label for="country" class="form-label">Country</label>
              <select class="form-select" id="country" name="pais" required>
                <option value="">Selecciona...</option>
                <option>México</option>
              </select>
              <div class="invalid-feedback">
                Selecciona un país valido.
              </div>
            </div>

            <div class="col-md-4">
              <label for="state" class="form-label">State</label>
              <select class="form-select" id="state" name="estado" required>
                <option value="">Selecciona...</option>
                <option>Jalisco</option>
              </select>
              <div class="invalid-feedback">
                Selecciona un estado válido.
              </div>
            </div>

            <div class="col-md-3">
              <label for="zip" class="form-label">Código Postal</label>
              <input type="text" class="form-control" id="zip" name="codigoPostal" placeholder="" required>
              <div class="invalid-feedback">
                Introduce el código postal
              </div>
            </div>
          </div>

          <hr class="my-4">

          <h4 class="mb-3">Pago</h4>

          <div class="row gy-3">
            <div class="col-md-6">
              <label for="cc-name" class="form-label">Nombre en la tarjeta</label>
              <input type="text" class="form-control" id="cc-name" name="nombreCC" placeholder="" required>
              <small class="text-body-secondary">Introduzca su nombre completo como aparece en su tarjeta.</small>
              <div class="invalid-feedback">
                Introduzca su nombre completo como aparece en su tarjeta.
              </div>
            </div>

            <div class="col-md-6">
              <label for="cc-number" class="form-label">Número de tarjeta</label>
              <input type="text" class="form-control" id="cc-number" name="numCC" placeholder="" required>
              <div class="invalid-feedback">
                Introduzca su número de tarjeta.
              </div>
            </div>

            <div class="col-md-3">
              <label for="cc-expiration" class="form-label">Fecha de expiración</label>
              <input type="text" class="form-control" id="cc-expiration" name="ccExp" placeholder="" required>
              <div class="invalid-feedback">
                Introduzca la fecha de expiración.
              </div>
            </div>

            <div class="col-md-3">
              <label for="cc-cvv" class="form-label">CVV</label>
              <input type="text" class="form-control" id="cc-cvv" name="ccCVV" placeholder="" required>
              <div class="invalid-feedback">
                Introduzca el código de seguridad.
              </div>
            </div>
          </div>

          <hr class="my-4">

          <button class="w-100 btn btn-primary btn-lg" type="submit" name="enviarDatos">Confirmar su orden</button>
        </form>
      </div>
    </div>
  </main>

  <footer class="my-5 pt-5 text-body-secondary text-center text-small">
    <p class="mb-1">&copy; 2023 Waves</p>

  </footer>
</div>

</html>
