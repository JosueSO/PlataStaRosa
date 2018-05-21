<?php
$conexion;

MakeConnection();

function MakeConnection(){
    global $conexion;
    $user = "postgres";
    $password = "Alijonas_963";
    $dbname = "central";
    $port = "5432";
    $host = "localhost";
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
    $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());

    $query = "select * from \"Fragmentos\" WHERE tabla='SuministraPieza'";
    $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");

    if($fila=pg_fetch_array($resultado)){
        $t_sum = $fila[1];
    }

    actualizaSuministra($t_sum);
}

function actualizaSuministra($t_sum){
    $user = "postgres";
    $password = "Alijonas_963";
    $dbname = "sta_rosa";
    $port = "5432";
    $host = "localhost";
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

    try{
        $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());

        $query = "UPDATE \"".$t_sum."\" SET total =".$_POST['total']." WHERE id_suministra = ".$_POST['idSum'];
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
    }
    catch(PDOException $ex){
        echo 'Error en la conexión' . $ex->getMessage();
    }
}
?>