<?php
require '../includes/funciones.php';
//revisar inicio de sesion
$auth=autenticado();
if (!$auth) {
    header('location: /login.php?mensaje=1');
}


//importar la conexion a la BD
require '../includes/config/database.php';
$db = conectarDB();
//escribir el jQuery
$query = "SELECT * FROM propiedades";
//Consultar la BD
$resultado = mysqli_query($db, $query);

//muestra el mensaje condicional
$mensaje = $_GET['mensaje'] ?? null; //similar al isset() asigna null por defecto al mensaje
//var_dump($mensaje);


if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $id=$_POST['id'];
    $id=filter_var($id,FILTER_VALIDATE_INT);
    
    if ($id) {

        //extraer nombre de imagen
        $query="SELECT imagen FROM propiedades WHERE id=$id";
        $resultado=mysqli_query($db,$query);
        $propiedad=mysqli_fetch_assoc($resultado);
        $imagenpropiedad=$propiedad['imagen'];

        //eliminar imagen
        $carpetaimagenes = '../imagenes/';
        unlink($carpetaimagenes.$imagenpropiedad);
        //eliminar la propiedad
        $query="DELETE FROM propiedades WHERE id=$id";
        $resultado=mysqli_query($db,$query);
         if ($resultado) {
             header('location: /admin?mensaje=3');
         }
    }

}

//incluir el template
incluirTemplate('header');

?>
<main class="contenedor seccion">
    <h1>Administrador de bienes raices</h1>
    <?php if ($mensaje == 1) : ?>
        <p class="alerta exito">Anuncio Creado Correctamente</p>
        <?php elseif ($mensaje == 2) :?>
        <p class="alerta exito">Actualizado Correctamente</p>
        <?php elseif ($mensaje == 3) :?>
        <p class="alerta exito">Eliminado Correctamente</p>
    <?php endif; ?>
    <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva propiedad</a>

    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <!-- mostrar los resultados -->
            <?php while($propiedad=mysqli_fetch_assoc($resultado)):?>
            <tr>
                <td><?php echo $propiedad['id']; ?></td>
                <td><?php echo $propiedad['titulo']; ?></td>
                <td><img src="/imagenes/<?php echo $propiedad['imagen']; ?>" alt="imagen casa" class="imagen-tabla"></td>
                <td>$<?php echo $propiedad['precio']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?php echo $propiedad['id'];?>">
                        <input type="submit" class="boton-rojo-block" value="Eliminar">

                    </form>
                    <a class="boton-verde-block" href="admin\propiedades\actualizar.php<?php echo '?id='.$propiedad['id'];?>">Actualizar</a>
                </td>
            </tr>
            <?php endwhile;?>
        </tbody>

    </table>

</main>

<?php

//cerrar la conexion
mysqli_close($db);//es opcional, PHP lo hace normalmente

incluirTemplate('footer');
?>