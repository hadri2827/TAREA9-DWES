<html>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DWES Tarea 7</title>
    <style>
        @import url("estilo.css");
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans&display=swap');
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
</head>

<body>
    <header>
        <h1>TAREA 7 - DWES</h1>
        <h1>Adrián Hernández Blanco - 47225617Q</h1>

        <div class="nav">
            <ul>
                <li><a href="https://adrianhernandezblanco.000webhostapp.com/Tarea7/Code/cliente.php?action=get_lista_autores">Lista de autores</a></li>
                <li><a href="https://adrianhernandezblanco.000webhostapp.com/Tarea7/Code/cliente.php?action=get_lista_libros">Lista de libros</a></li>
                <li><a href="https://adrianhernandezblanco.000webhostapp.com/Tarea7/Code/cliente.php?action=consulta_libro">Búsqueda de libros</a></li>
                <li><a href="https://adrianhernandezblanco.000webhostapp.com/Tarea7/Code/cliente.php?action=consulta_autor">Búsqueda de autores</a></li>
            </ul>
        </div>
    </header>

    <?php
    // Si se ha hecho una peticion que busca informacion de un autor "get_datos_autor" a traves de su "id"...
    if (isset($_GET["action"]) && isset($_GET["id"]) && $_GET["action"] == "get_datos_autor") {
        //Se realiza la peticion a la api que nos devuelve el JSON con la información del autor seleccionado
        $app_info = file_get_contents('https://adrianhernandezblanco.000webhostapp.com/Tarea7/Code/api.php?action=get_datos_autor&id=' . $_GET["id"]);
        // Se decodifica el fichero JSON y se convierte a array
        $app_info = json_decode($app_info, true);
    ?>
        <!-- Se muestra los datos del autor y sus libros. Si se hace clic en el titulo, vamos a ese libro en concreto -->
        <h2>Información del autor <?php echo $app_info["nombre"] ?> <?php echo $app_info["apellidos"] ?>:</h2>
        <table>
            <tr>
                <td class="titulo">Nombre: </td>
                <td> <?php echo $app_info["nombre"] ?></td>
            </tr>
            <tr>
                <td class="titulo">Apellidos: </td>
                <td> <?php echo $app_info["apellidos"] ?></td>
            </tr>
            <tr>
                <td class="titulo">Fecha de nacimiento: </td>
                <td> <?php echo $app_info["nacionalidad"] ?></td>
            </tr>
        </table>
        <br />

        <h2>Libros de <?php echo $app_info["nombre"] ?> <?php echo $app_info["apellidos"] ?>:</h2>
        <table>
            <tr class="columna">
                <td>ID: </td>
                <td>Título: </td>
                <td>Fecha publicación: </td>
            </tr>
            <?php foreach ($app_info["libros"] as $libro) : ?>
                <tr>
                    <td><?php echo $libro["id"] ?></td>
                    <td><a href="<?php echo 'https://adrianhernandezblanco.000webhostapp.com/Tarea7/Code/cliente.php?action=get_datos_libro&id=' . $libro["id"] ?>">
                            <?php echo $libro["titulo"] ?></a></td>
                    <td><?php echo $libro["f_publicacion"] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php
    } elseif (isset($_GET["action"]) && $_GET["action"] == "get_lista_libros") {
        //Se realiza la peticion a la api que nos devuelve el JSON con la información de los libros
        $app_info = file_get_contents('https://adrianhernandezblanco.000webhostapp.com/Tarea7/Code/api.php?action=get_lista_libros');
        // Se decodifica el fichero JSON y se convierte a array
        $app_info = json_decode($app_info, true);
    ?>
        <!-- Mostramos el ID y titulo de todos los libros -->
        <table>
            <tr class="columna">
                <td>ID: </td>
                <td>Título: </td>
            </tr>
            <?php foreach ($app_info as $libro) : ?>
                <tr>
                    <td><?php echo $libro["id"] ?></td>
                    <td><a href="<?php echo 'https://adrianhernandezblanco.000webhostapp.com/Tarea7/Code/cliente.php?action=get_datos_libro&id=' . $libro["id"] ?>">
                            <?php echo $libro["titulo"] ?></a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php
    } elseif (isset($_GET["action"]) && isset($_GET["id"]) && $_GET["action"] == "get_datos_libro") {
        //Se realiza la peticion a la api que nos devuelve el JSON con la información de un libro en concreto.
        $app_info = file_get_contents('https://adrianhernandezblanco.000webhostapp.com/Tarea7/Code/api.php?action=get_datos_libro&id=' . $_GET["id"]);
        // Se decodifica el fichero JSON y se convierte a array
        $app_info = json_decode($app_info, true);
    ?>
        <!-- Se muestra la información de todos los libros y si se hace clic en el apellido del autor, mostramos su información -->
        <h2>Datos del libro "<?php echo $app_info["titulo"] ?>":</h2>
        <table>
            <tr>
                <td class="titulo">ID: </td>
                <td> <?php echo $app_info["id"] ?></td>
            </tr>
            <tr>
                <td class="titulo">Título: </td>
                <td> <?php echo $app_info["titulo"] ?></td>
            </tr>
            <tr>
                <td class="titulo">Fecha de publicación: </td>
                <td> <?php echo $app_info["f_publicacion"] ?></td>
            </tr>
            <tr>
                <td class="titulo">ID del autor: </td>
                <td> <?php echo $app_info["id_autor"] ?></td>
            </tr>
            <tr>
                <td class="titulo">Nombre: </td>
                <td> <?php echo $app_info["nombre"] ?></td>
            </tr>
            <tr>
                <td class="titulo">Apellidos: </td>
                <td><a href="<?php echo "https://adrianhernandezblanco.000webhostapp.com/Tarea7/Code/cliente.php?action=get_datos_autor&id=" . $app_info["id_autor"] ?>">
                        <?php echo $app_info["apellidos"] ?></a></td>
            </tr>
        </table>
    <?php
    } elseif (isset($_GET["action"]) && $_GET["action"] == "consulta_libro") {

    ?>
        <h2>Búsqueda de libros:</h2>
        <form>
            Título: <input type="text" id="texto" pattern="[A-Za-z]">
        </form>
        <h2>Sugerencias:</h2>
        <p id="sugerencias"></p>
        <script>
            $(document).ready(function() {
                alert("Bienvenido al buscador de libros.")
                confirm("¿Desea continuar?")
                $("#texto").keyup(function() {
                    $("#sugerencias").empty();
                    $.getJSON("https://adrianhernandezblanco.000webhostapp.com/Tarea7/Code/api.php?action=consulta_libro&q=" + $("#texto").val(), function(data) {
                        console.log(data);
                        if ($.isArray(data)) {
                            var str = "";
                            for (let elemento of data) {
                                str += "<b>" + elemento.titulo + "</b>" + "<br>";
                            }
                            $("#sugerencias").html(str);
                        } else {
                            $("#sugerencias").html("<b>" + data + "</b>" + "<br>")
                        }
                    });
                });
            });
        </script>
    <?php

    } elseif (isset($_GET["action"]) && $_GET["action"] == "consulta_autor") {

    ?>
        <h2>Búsqueda de autores y sus libros:</h2>
        <form>
            Apellido del autor: <input type="text" id="texto" pattern="[A-Za-z]">
        </form>
        <h2>Sugerencias:</h2>
        <p id="sugerencias"></p>
        <script>
            $(document).ready(function() {
                $("#texto").keyup(function() {
                    $("#sugerencias").empty();
                    $.getJSON("https://adrianhernandezblanco.000webhostapp.com/Tarea7/Code/api.php?action=consulta_autor&q=" + $("#texto").val(), function(data) {
                        if ($.isArray(data)) {
                            var str = "";
                            str += "Libros escritos por " + "<b>" + data[0].nombre + "&nbsp;" + data[0].apellidos + ":</b><br><br>";
                            for (let elemento of data) {
                                str += "<b>" + elemento.titulo + " - </b>";
                                str += elemento.f_publicacion + "<br>";
                            }
                            $("#sugerencias").html(str);
                        } else {
                            $("#sugerencias").html("<b>" + data + "</b>" + "<br>")
                        }
                    });
                });
            });
        </script>
    <?php
    } else {
        // Pedimos al la api que nos devuelva una lista de autores. La respuesta se da en formato JSON
        $lista_autores = file_get_contents('https://adrianhernandezblanco.000webhostapp.com/Tarea7/Code/api.php?action=get_lista_autores');
        // Convertimos el fichero JSON en array
        $lista_autores = json_decode($lista_autores, true);
    ?>
        <h2>Lista de autores</h2>
        <ul>
            <!-- Mostramos una entrada por cada autor -->
            <?php foreach ($lista_autores as $autores) : ?>
                <li class="autores">
                    <!-- Enlazamos cada nombre de autor con su informacion (primer if) -->
                    <a href="<?php echo "https://adrianhernandezblanco.000webhostapp.com/Tarea7/Code/cliente.php?action=get_datos_autor&id=" . $autores["id"]  ?>">
                        <?php echo $autores["nombre"] . " " . $autores["apellidos"] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php
    }
    ?>

    <footer>
        <hr>
        <p>Página elaborada por: Adrián Hernández Blanco</p>
    </footer>
</body>

</html>