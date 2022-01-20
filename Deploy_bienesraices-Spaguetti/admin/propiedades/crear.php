<?php

require '../../includes/funciones.php';
//revisar inicio de sesion
$auth=autenticado();
if (!$auth) {
    header('location: /login.php?mensaje=1');
}

//base de datos
require '../../includes/config/database.php';
$db = conectarDB();

//crear una consulta
$consulta = "SELECT * FROM vendedores";
$resultadoc = mysqli_query($db, $consulta);

//Arreglo con mensajes de error
$errores = [];
//Declarar variables, para que no se borre la info del formulario
$titulo = '';
$precio = '';
$descripcion = '';
$habitaciones = '';
$wc = '';
$estacionamiento = '';
$vendedorid = '';
//Ejecutar el codigo cuando se envia el formulario
if ($_SERVER["REQUEST_METHOD"] === 'POST') {

    echo "<pre>";
    var_dump($_POST);
    echo "</pre>";

    
    echo "<pre>";
    var_dump($_FILES);
    echo "</pre>";

    //exit;

    $titulo = mysqli_real_escape_string($db, $_POST['titulo']);
    $precio = mysqli_real_escape_string($db, $_POST['precio']);
    $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);
    $habitaciones = mysqli_real_escape_string($db, $_POST['habitaciones']);
    $wc = mysqli_real_escape_string($db, $_POST['wc']);
    $estacionamiento = mysqli_real_escape_string($db, $_POST['estacionamiento']);
    $vendedorid = mysqli_real_escape_string($db, $_POST['vendedor']);
    $creado=date('Y/m/d');

    //Asignar files
    $imagen=$_FILES['imagen'];


    if (!$titulo) {
        $errores[] = "Debes añadir un titulo";
    }
    if (!$precio) {
        $errores[] = "Debes añadir un precio";
    }
    if (strlen($descripcion) < 20) {
        $errores[] = "La descripcion debe contener al menos 20 caracteres";
    }
    if (!$habitaciones) {
        $errores[] = "El numero de habitaciones es obligatorio";
    }
    if (!$wc) {
        $errores[] = "El numero de baños es obligatorio";
    }
    if ($estacionamiento === '') {
        $errores[] = "El numero de estacionamientos es obligatorio";
    }
    if (!$vendedorid) {
        $errores[] = "Debes elegir un vendedor";
    }
    //Validar imagen
    $medida=1000*100;
    if (!$imagen['name']) {
        $errores[] = "La imagen es Obligatoria";
    }elseif($imagen['size']>$medida){ //validar por tamaño (100Kb max)
        $errores[]="La imagen debe pesar menos de 100Kb";
    }elseif($imagen['error']>0){ 
        $errores[]="La imagen tiene un error";
    }

    // echo "<pre>";
    // var_dump($errores);
    // echo "</pre>";

    //Revisar el arreglo de errores
    if (empty($errores)) {
        //Subir los archivos
        //Crear carpeta
        $carpetaimagenes='../../imagenes/';
        if (!is_dir($carpetaimagenes)) {
            mkdir($carpetaimagenes);
        }
        //Generar nombre
        $nombreimagen=md5(uniqid(rand(),true)).".jpg";
        //var_dump($nombreimagen);

        //subir imagen
        move_uploaded_file($imagen['tmp_name'],$carpetaimagenes.$nombreimagen);
        

        //insertar en la base de datos
        $query = "INSERT INTO propiedades (titulo,precio,imagen,descripcion,habitaciones,
        wc,estacionamiento,creado,vendedorid) VALUES ('$titulo','$precio','$nombreimagen','$descripcion',
        '$habitaciones','$wc','$estacionamiento','$creado','$vendedorid')";

        // echo $query;
        $resultado = mysqli_query($db, $query);


        header('location: /admin?mensaje=1');//debe ir antes del HTML, si va despues no se puede
    }
}


incluirTemplate('header');
?>
<main class="contenedor seccion">
    <h1>Crear</h1>
    <a href="/admin" class="boton boton-verde">volver</a>



    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>



    <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
        <fieldset>
            <legend>Informacion General</legend>

            <Label for="titulo">Titulo</Label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo $titulo; ?>">

            <Label for="precio">Precio</Label>
            <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio; ?>">

            <Label for="imagen">Imagen</Label>
            <input type="file" id="imagen" name="imagen" accept="image/jpeg, image/png">

            <Label for="descripcion">Descripcion</Label>
            <textarea id="descripcion" name="descripcion" placeholder="Aqui va la descripcion"><?php echo $descripcion; ?></textarea>

        </fieldset>

        <fieldset>
            <legend>Informacion de la propiedad</legend>

            <Label for="habitaciones">Habitaciones</Label>
            <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej:3" min="1" max="9" value="<?php echo $habitaciones; ?>">

            <Label for="wc">baños</Label>
            <input type="number" id="wc" name="wc" placeholder="Ej:3" min="1" max="9" value="<?php echo $wc; ?>">

            <Label for="estacionamiento">Espacio de estacionamiento</Label>
            <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej:3" min="0" max="9" value="<?php echo $estacionamiento; ?>">

        </fieldset>

        <fieldset>
            <legend>Seleccione el Vendedor</legend>
            <select name="vendedor">
                <option value="">--Seleccione una opcion--</option>
                <?php while ($vendedor = mysqli_fetch_assoc($resultadoc)) : ?>
                    <option <?php echo $vendedorid === $vendedor['id'] ? 'Selected' : '' ?> value="<?php echo $vendedor['id']; ?>"><?php echo $vendedor['nombre'] . ' ' . $vendedor['apellido']; ?></option>
                <?php endwhile; ?>
            </select>
        </fieldset>
        <input type="submit" value="Crear Propiedad" class="boton boton-verde">
    </form>

</main>

<?php
incluirTemplate('footer');
?>