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
if (isset($_POST["login"])){
  $credential = $_POST["credentials"];
  $password = $_POST["password"];

  require_once "../database.php";
  $sql = "SELECT * FROM users WHERE email = '$credential' OR username = '$credential'";
  $result = mysqli_query($conn,$sql);
  $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
  if ($user) {
    if (password_verify($password,$user["password"])) {
      session_start();
      $_SESSION["user"] = $user["username"];
      $_SESSION["privilege"] = $user["privilege"];
      $_SESSION["Id"] = $user["Id"];
      header("Location: ../../index.php");
      die();
    }
    else{
    echo "<div class='alert alert-danger'>La contraseña es incorrecta.</div>";
    }
  }
  else{
    echo "<div class='alert alert-danger'>La cuenta no existe.</div>";
  }

}


?>


<main class="form-signin w-100 m-auto">
    <form action="admin-login.php" method="post">
        <a href="../../index.php"><img class="mb-4" src="../../assets/logos/minilogo.png" alt="" width="300" height="200"></a>
        <h1 class="h3 mb-3 fw-normal">Inicia sesión</h1>

        <div class="form-floating">
        <input type="text" class="form-control" id="floatingInput" placeholder="Ingresa username o email " name="credentials">
        <label for="floatingInput">Nombre de Usuario o Email</label>
        </div>
        <div class="form-floating">
        <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
        <label for="floatingPassword">Contraseña</label>
        </div>
        <button class="btn btn-primary w-100 py-2" type="submit" name="login">Entrar</button>
        <p class="mt-5 mb-3 text-body-secondary" id="redirect-text">
            ¿No tienes una cuenta? <br>
            <a href="admin-signin.php">Registrate</a> <br>
            &copy;2023
        </p>
    </form>
</main>
<script src="../../bootstrap.bundle.min.js"></script>

    </body>
</html>
