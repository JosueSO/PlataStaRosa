<!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  </head>
  <body>
      <?php
          MakeConnection();
      ?>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>

<?php
  $user = "postgres"; //MI IP 148.224.56.31
  $password = "Alijonas_963";
  $dbname = "central";
  $port = "5432";
  $host = "localhost"; 
  $cadenaConexion;

function MakeConnection(){

  global $user = "postgres"; //MI IP 148.224.56.31
  global $password = "Alijonas_963";
  global $dbname = "central";
  global $port = "5432";
  global $host = "localhost";
  
  global $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
  
  $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
  echo "<h3>Conexion Exitosa PHP - PostgreSQL</h3><hr><br>";
  
  $query = "select * from \"Atributos\"";
  $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
  var_dump($resultado);
  $fila=pg_fetch_array($resultado);
  echo $fila[1];
}

function Inserta_Pieza(){
  $query = "insert into \"Pieza\" (codigo, familia, categoria, proveedor, precio, existencia) 
            values (" .$codigo. "," . $familia . ",". $categoria . "," . $proveedor . "," . $precio . "," . $existencia . ")";
  $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
  var_dump($resultado);
  while($resultado){
    //imprimir todos los resultados en una tabla
  }
  $fila=pg_fetch_array($resultado);
  echo $fila[1];
}
    
?>