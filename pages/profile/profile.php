<?php 
session_start();
if (!isset($_SESSION["user"])){
  header("Location: pages/login/admin-login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Waves</title>
    <link href="../../bootstrap.min.css" rel="stylesheet">

</head>
<body>

<?php 

require "../database.php";

if ($conn->connect_error){
    die("Falló la conexión: " . $conn->connect_error);
}

//Definir la consulta SQL
$userId = $_SESSION["Id"];
$sql = "SELECT * FROM users WHERE Id = '$userId'";

$result = $conn->query($sql);
$datosPerfil = $result->fetch_assoc();

if(isset($_POST["submit"])){
    $username = $_POST["username"];
    $email = $_POST["correo"];
    $password = $_POST["password"];
    $repetirPass = $_POST["repeatPass"];

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $errors = array();


    if (empty($email) OR empty($username) OR empty($password) OR empty($repetirPass)){
        array_push($errors,"Uno o varios campos no fueron introducidos.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        array_push($errors,"El correo introducido no es valido.");
      }
    if (strlen($password) < 8){
        array_push($errors,"La contraseña debe ser mayor a 8 caracteres.");
    }
    
    if($passwordHash == $datosPerfil["password"]){
        array_push($errors,"La contraseña nueva es igual a la anterior.");
    }

    if ($password !== $repetirPass){
        array_push($errors,"Las contraseñas no coinciden.");
    }
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $rowCount = mysqli_num_rows($result);
    if ($rowCount>0){
      array_push($errors, "Ya existe una cuenta con ese nombre de usuario.");
    }

    if(count($errors) > 0){
        foreach($errors as $error){
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
    else{
        $sql ="UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
        $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
        if ($prepareStmt){
            mysqli_stmt_bind_param($stmt,"ssss", $username, $email, $passwordHash, $datosPerfil["Id"]);
            mysqli_stmt_execute($stmt);
            echo "<div class='alert alert-success'>Actualización exitosa.</div>";
        }
        else{
            die("Algo salió mal");
        }
    }

}

?>
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
<br>
<div class="container-sm">
<div class="row gutters">
<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
<div class="card h-40">
	<div class="card-body">
		<div class="account-settings">
			<div class="user-profile">
            <p>Revisa y edita tu perfil.</p>
                <?php 
                echo '<h5>' . $datosPerfil["username"] .'</h5>';
                echo '<h6>'. $datosPerfil["email"].'</h6>';
                echo '<h7> ID de Usuario: '. $datosPerfil["Id"] . '</h7> <br>';
                echo '<a href="../login/logout.php" class="btn btn-warning">Cerrar sesión</a>';
                ?>
                
			</div>
		</div>
	</div>
</div>
</div>
<div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
<div class="card h-100 ">
	<div class="card-body">
		<div class="row gutters">
            <form action="profile.php" method="POST">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <h6 class="mb-2 text-primary">Detalles de la cuenta</h6>
                    </div>
                    
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="userName">Nombre de usuario</label>
                            <?php echo '<input type="text" name ="username" class="form-control" id="userName" placeholder="Ingresa tu nombre de usuario" value='. $datosPerfil["username"].'>';
                            ?>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="eMail">Email</label>
                            <?php echo '<input type="email" name="correo" class="form-control" id="eMail" placeholder="Ingresa tu correo electrónico" value='. $datosPerfil["email"] .'>' ?>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-control" name="password" id="repeatPassword" placeholder="Introduce una Contraseña">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="repeatPassword">Vuelve a ingresar tu contraseña</label>
                            <input type="password" class="form-control" name="repeatPass" id="repeatPassword" placeholder="Ingresa de nuevo la contraseña">
                        </div>
                    </div>
                </div>
                <br>    
                <div class="row gutters">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="text-right">
                            <button class="btn btn-primary" type="submit" name="submit">Actualizar</button>
                        </div>
                    </div>
                </div>
            </form>
			
	</div>
</div>
</div>
</div>
</div>   



</body>
</html>