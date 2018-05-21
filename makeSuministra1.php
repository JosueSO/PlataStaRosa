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

    $query = "select * from \"Fragmentos\" WHERE tabla='Proveedor'";
    $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");

    if($fila=pg_fetch_array($resultado)){
        $t_prov = $fila[1];
    }

    $query = "select * from \"Fragmentos\" WHERE tabla='SuministraPieza'";
    $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");

    if($fila=pg_fetch_array($resultado)){
        $t_sum = $fila[1];
    }

    insertaSuministra($t_prov, $t_sum);
}

function insertaSuministra($t_prov, $t_sum){
    $user = "postgres";
    $password = "Alijonas_963";
    $dbname = "sta_rosa";
    $port = "5432";
    $host = "localhost";
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

    try{
        $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
        $query = "SELECT * FROM \"$t_prov\" WHERE nombre = '".$_POST['Proveedor']."'";

        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");

        if($fila = pg_fetch_array($resultado)){
            $idProveedor = $fila[0];

            $query = "INSERT INTO \"".$t_sum."\"(id_proveedor,total,fecha) VALUES(".$idProveedor.",0,CURRENT_DATE)";
            $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
            
            $query = "SELECT id_suministra FROM \"".$t_sum."\" ORDER BY id_suministra DESC Limit 1";
            $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
            $fila = pg_fetch_array($resultado);
            echo $fila[0];
        }
    }
    catch(PDOException $ex){
        echo 'Error en la conexión' . $ex->getMessage();
    }
}
?>