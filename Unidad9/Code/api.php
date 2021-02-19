<?php

/**
 * @param string $sv Nombre del servidor
 * @param string $user Usuario con el que nos vamos a conectar a la BD
 * @param string $pass Contraseña del usuario
 * @param string $bd Base de datos a la que nos estamos conectando
 * 
 * Método de conexión a la base de datos.
 *
 * Tomamos los valores de las propiedades de la clase y creamos un objeto
 * de conexión a la base de datos. Si la conexión está bien, cambiamos
 * el charset a utf8 y devolvemos el objeto de conexión.
 * Si la conexión es erronea, devolvemos null.
 * 
 * @return object devolvemos el objeto de conexión o null.
 */
function conexion($sv="localhost", $user="id15405522_usuario", $pass="1z2x3c4v5bA@", $bd="id15405522_libro")
//function conexion($sv = "localhost", $user = "usuario", $pass = "usuario", $bd = "libros")
{
  $mysqli = @new mysqli($sv, $user, $pass, $bd);
  if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
    return null;
  } else {
    $mysqli->set_charset("utf8");
    return $mysqli;
  }
}

/**
 * Método para mostrar la información de los autores
 * 
 * Comenzamos creando la conexión. Después, muestra todos los autores.
 * @return array $lista_autores array asociativo con los datos del autor.
 */
function get_lista_autores()
{
  $mysqli = conexion();
  $sql = "SELECT * FROM autor";
  $resultado = $mysqli->query($sql);

  $lista_autores = $resultado->fetch_all(MYSQLI_ASSOC);
  $resultado->free();

  return $lista_autores;
}

/**
 * @param int $id ID del autor que queremos mostrar
 * 
 * Método para mostrar la información de los autores
 * 
 * Comenzamos creando la conexión. Después, con el ID, busca ese autor.
 * @return array $info_autor array asociativo con los datos del autor.
 */
function get_datos_autor($id)
{
  $mysqli = conexion();
  $sql = "SELECT * FROM autor WHERE id=" . $id;
  $resultado = $mysqli->query($sql);

  $info_autor = $resultado->fetch_all(MYSQLI_ASSOC);
  $resultado->free();

  $sql2 = "SELECT * FROM libro WHERE id_autor=" . $id;
  $resultado = $mysqli->query($sql2);

  $info_libros = $resultado->fetch_all(MYSQLI_ASSOC);
  $resultado->free();

  $info_autor[0]["libros"] = $info_libros;

  return $info_autor[0];
}

/**
 * Método para mostrar la ID y TITULO de los libros.
 * 
 * Comenzamos creando la conexión. Después, muestra los ID y Titulos de los libros.
 * @return array $lista_libros array asociativo con los libros.
 */
function get_lista_libros()
{
  $mysqli = conexion();
  $sql = "SELECT id, titulo FROM libro";
  $resultado = $mysqli->query($sql);

  $lista_libros = $resultado->fetch_all(MYSQLI_ASSOC);
  $resultado->free();

  return $lista_libros;
}

/**
 * @param int $id ID del libro que queremos mostrar
 * 
 * Método para mostrar la información de un libro
 * 
 * Comenzamos creando la conexión. Después, con el ID, busca ese libro.
 * @return array $info_libro array asociativo con los datos del libro.
 */
function get_datos_libro($id)
{
  $mysqli = conexion();
  $sql = "SELECT L.*, A.nombre, A.apellidos FROM `libro` AS L INNER JOIN autor AS A ON L.id_autor=A.id WHERE L.id = " . $id;
  $resultado = $mysqli->query($sql);

  $info_libro = $resultado->fetch_all(MYSQLI_ASSOC);
  $resultado->free();

  return $info_libro[0];
}

/**
 * @param int $q valor del texto por el que preguntamos
 * 
 * Método para buscar un libro por su titulo
 * 
 * Comenzamos verificando el formato de lo que le pasamos por parámetro.
 * Si está vacío mostramos un mensaje. 
 * Si coincide con la expresión regular, quiere decir que tiene formato correcto. Creamos la conexión y buscamos coincidencias en la base de datos.
 * Si no es correcto el formato, nos muestra un error.
 * @return array $consulta_libro devuelve el error o los datos de la consulta.
 */
function consulta_libro($q)
{
  //Comprobamos el formato
  if ($q === "") {
    //Está vacío así que devolvemos el error.

    $consulta_libro = "No hay sugerencias. Escribe una letra.";
    return $consulta_libro;
  } elseif (preg_match("/^[A-Za-z\s\W]+$/", $q) == 1) {
    //Comprobamos que solo contenga letras.

    $mysqli = conexion();
    $sql = "SELECT titulo FROM libro WHERE titulo LIKE '%$q%'";
    $resultado = $mysqli->query($sql);

    $consulta_libro = $resultado->fetch_all(MYSQLI_ASSOC);
    $resultado->free();

    return $consulta_libro;
  } else {
    $consulta_libro = "Formato incorrecto. Escribe una letra.";
    return $consulta_libro;
  }
}

/**
 * @param int $q valor del texto por el que preguntamos
 * 
 * Método para buscar un autor por su nombre
 * 
 * Comenzamos verificando el formato de lo que le pasamos por parámetro.
 * Si está vacío mostramos un mensaje. 
 * Si coincide con la expresión regular, quiere decir que tiene formato correcto. Creamos la conexión y buscamos coincidencias en la base de datos.
 * Si no es correcto el formato, nos muestra un error.
 * @return array $consulta_autor devuelve el error o los datos de la consulta.
 */
function consulta_autor($q)
{
  //Comprobamos el formato
  if ($q === "") {
    //Está vacío así que devolvemos el error.

    $consulta_autor = "No hay sugerencias. Escribe una letra.";
    return $consulta_autor;
  } elseif (preg_match("/^[A-Za-z\s\W]+$/", $q) == 1) {
    //Comprobamos que solo contenga letras.

    $mysqli = conexion();
    $sql = "SELECT A.nombre, A.apellidos, L.* FROM `autor` AS A INNER JOIN libro AS L ON A.id= L.id_autor WHERE A.apellidos LIKE '%$q%'";
    $resultado = $mysqli->query($sql);

    $consulta_autor = $resultado->fetch_all(MYSQLI_ASSOC);
    $resultado->free();

    return $consulta_autor;
  } else {
    $consulta_autor = "Formato incorrecto. Escribe una letra.";
    return $consulta_autor;
  }
}

//Variable con los valores posibles de la URL
$posibles_URL = array("get_lista_autores", "get_datos_autor", "get_lista_libros", "get_datos_libro", "consulta_libro", "consulta_autor");

$valor = "Ha ocurrido un error";

//Evaluamos si se ha seteado la accion y si dicha acción se encuentra dentro de las posibles
if (isset($_GET["action"]) && in_array($_GET["action"], $posibles_URL)) {
  switch ($_GET["action"]) {
      //Primer caso, lista de autores completa.
    case "get_lista_autores":
      $valor = get_lista_autores();
      break;

      //Segundo caso, datos de autor en concreto.
    case "get_datos_autor":
      if (isset($_GET["id"]))
        $valor = get_datos_autor($_GET["id"]);
      else
        $valor = "Argumento no encontrado";

      break;

      //Tercer caso, lista de libros completa.
    case "get_lista_libros":
      $valor = get_lista_libros();

      break;

      //Cuarto caso, datos de un libro en concreto.
    case "get_datos_libro":
      if (isset($_GET["id"]))
        $valor = get_datos_libro($_GET["id"]);
      else
        $valor = "Argumento no encontrado";

      break;

      //Quinto caso, buscamos el título de un libro.
    case "consulta_libro":
      if (isset($_GET["q"])) {
        $valor = consulta_libro($_GET["q"]);
      } else {
        $valor = "No hay sugerencias";
      }
      break;

      //Sexto caso, buscamos el autor con AJAX.
    case "consulta_autor":
      if (isset($_GET["q"])) {
        $valor = consulta_autor($_GET["q"]);
      } else {
        $valor = "No hay sugerencias";
      }
    break;
  }
}

//devolvemos los datos serializados en JSON
exit(json_encode($valor));
