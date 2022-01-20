<?php

//importar la conexion a la BD
require 'includes/config/database.php'; //como se llama desde index.php
$db = conectarDB();


//muestra el mensaje condicional
$mensaje = $_GET['mensaje'] ?? null;


var_dump($_POST);

$errores = [];

if ($_SERVER["REQUEST_METHOD"] === 'POST') {

    $email = mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
    $password = mysqli_real_escape_string($db, $_POST['password']);


    if (!$email) {
        $errores[] = 'El email es obligatorio o no es valido';
    }
    if (!$password) {
        $errores[] = 'El password es Obligatorio o no es valido';
    }
    if (empty($errores)) {
        $query = "SELECT * FROM usuarios WHERE email='$email'";
        //var_dump($query);
        //Consultar la BD
        $resultado = mysqli_query($db, $query);
        //var_dump($resultado);
        if ($resultado->num_rows) {
            $usuario = mysqli_fetch_assoc($resultado);
            //obtener email y password
            $hashpassword = $usuario['password'];

            //verificar si el password es correcto
            $auth=password_verify($password,$hashpassword);//funcion para comparar password
            //var_dump($auth);

            if ($auth) {
                //usuario autenticado
                session_start();
                //llenar arreglo de la sesion
                $_SESSION['usuario']=$usuario['email'];
                $_SESSION['login']=true;

                header('location: /admin');
            }else {
                $errores[]='El password es incorrecto';
            }
        } else {
            $errores[] = 'El usuario no Existe';
        }
    }
}


require 'includes/funciones.php';
incluirTemplate('header');
?>
<main class="contenedor seccion contenido-centrado">
    <h1>Iniciar sesion</h1>

    <?php if ($mensaje == 1) : ?>
        <p id="mensajelogin" class="alerta error">Para acceder a este sitio debe iniciar sesion</p>
    <?php endif; ?>


    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>


    <form method="POST" class="formulario">
        <fieldset>
            <legend>Email y Password</legend>

            <label for="email">E-mail</label>
            <input type="email" name="email" placeholder="Tu Email" id="email">

            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Tu password" id="password">

        </fieldset>
        <input type="submit" value="Iniciar Sesion" class="boton-verde">
    </form>
</main>

<?php
incluirTemplate('footer');
?>