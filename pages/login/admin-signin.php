<?php 
session_start();
if (isset($_SESSION["user"])){
  header("Location: ../profile/profile.php");
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
    <title>Waves - Iniciar sesión</title>
    <link rel="stylesheet" href="login.css">
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
      
      #redirect-text > a {
        text-decoration: none;
      }

    </style>
  </head>
<body>

<?php 
if (isset($_POST["submit"])){
  $correo = $_POST["correo"];
  $password = $_POST["password"];
  $repetirPass = $_POST["repetirPass"];
  $username = $_POST["user"];

  $passwordHash = password_hash($password, PASSWORD_DEFAULT);
  $errors = array();

  if (empty($correo) OR empty($username) OR empty($password) OR empty($repetirPass)){
    array_push($errors,"Uno o varios campos no fueron introducidos.");
  }
  if (!filter_var($correo, FILTER_VALIDATE_EMAIL)){
    array_push($errors,"El correo introducido no es valido.");
  }
  if (strlen($password) < 8){
    array_push($errors,"La contraseña debe ser mayor a 8 caracteres.");
  }
  if ($password !== $repetirPass){
    array_push($errors,"Las contraseñas no coinciden.");
  }
  require_once "../database.php";
  $sql = "SELECT * FROM users WHERE email = '$correo'";
  $result = mysqli_query($conn, $sql);
  $rowCount = mysqli_num_rows($result);
  if ($rowCount>0){
    array_push($errors, "Ya existe una cuenta con ese correo.");
  }
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $sql);
  $rowCount = mysqli_num_rows($result);
  if ($rowCount>0){
    array_push($errors, "Ya existe una cuenta con ese nombre de usuario.");
  }



  if (count($errors)>0){
    foreach($errors as $error){
      echo "<div class='alert alert-danger'>$error</div>";
    }
  }
  else{
    $sql = "INSERT INTO users (email, username, password) VALUES (?,?,?)";
    $stmt = mysqli_stmt_init($conn);
    $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
    if ($prepareStmt) {
      mysqli_stmt_bind_param($stmt,"sss",$correo, $username,$passwordHash);
      mysqli_stmt_execute($stmt);
      echo "<div class='alert alert-success'>Registro exitoso.</div>";
    }
    else{
      die("Algo salió mal.");
    }
  }

  
}

?>
  
<main class="form-signin w-100 m-auto">
    <form action="admin-signin.php" method="post">
        <a href="../../index.php"><img class="mb-4" src="../../assets/logos/minilogo.png" alt="" width="300" height="200"></a>
        <h1 class="h3 mb-3 fw-normal">Crea una cuenta</h1>
        <div class="form-floating">
        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="correo">
        <label for="floatingInput">Correo electrónico</label>
        </div>
        <div class="form-floating">
        <input type="text" class="form-control" id="floatingInput" placeholder="Nombre de Usuario" name="user">
        <label for="floatingInput">Nombre de Usuario</label>
        </div>
        <div class="form-floating">
        <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
        <label for="floatingPassword">Contraseña</label>
        </div>
        <div class="form-floating">
        <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="repetirPass">
        <label for="floatingPassword">Confirmar contraseña</label>
        </div>
        <button class="btn btn-primary w-100 py-2" type="submit" name="submit">Registrarse</button>
        <p class="mt-5 mb-3 text-body-secondary" id="redirect-text">
            ¿Ya tienes una cuenta? <br>
            <a href="admin-login.php">Inicia sesión</a> <br>
            &copy;2023
        </p>
    </form>

</main>
<script src="../../bootstrap.bundle.min.js"></script>

    </body>
</html>
