<?php
$conexion;

$id_Pieza = $_POST['idPieza'];
$cantidad = $_POST['cantidad'];
$total = $_POST['total'];

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

    $query = "select * from \"Fragmentos\" WHERE tabla='Pieza'";
    $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");

    while($fila=pg_fetch_array($resultado)){
        if ($fila[3] == 1){
            if (p1($fila[1]) == true) {
                break;
            }
        }else{
            if (p2($fila[1]) == true) {
                break;
            }
        }
    }
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
            
            global $id_Pieza;
            global $cantidad;
            global $total;
            $unitario = $total / $cantidad;

            $query = "INSERT INTO \"".$t_sum."\" VALUES(".$idProveedor.",'".$id_Pieza."',".$cantidad.",".$unitario.",".$total.",CURRENT_DATE)";
            //echo $query;
            $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        }
    }
    catch(PDOException $ex){
        echo 'Error en la conexión' . $ex->getMessage();
    }
}

function p1($table){
    $user = "postgres";
    $password = "Alijonas_963";
    $dbname = "sta_rosa";
    $port = "5432";
    $host = "localhost";
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

    try{
        global $id_Pieza;
        global $cantidad;
        $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
        $query = "UPDATE \"$table\" SET cantidad = cantidad + ".$cantidad." WHERE id_pieza = '".$id_Pieza."'";
        //echo $query;
        $resultado = pg_affected_rows(pg_query($conexion, $query));

        if($resultado > 0){
            return true;
        }else{
            return false;
        }
    }
    catch(PDOException $ex){
        echo 'Error en la conexión' . $ex->getMessage();
        return;
    }
}

function p2($table){
    $usuario = "root";
    $fuente = "mysql:host=localhost;dbname=sta_rosa";
    try{
        $conexion = new PDO($fuente, $usuario);
        //echo 'Conexión establecida';
        
        global $id_Pieza;
        global $cantidad;
        $sql = "UPDATE $table SET cantidad = cantidad + ".$cantidad." WHERE id_pieza = '".$id_Pieza."'";
        //echo $sql;
        $resultado = $conexion->exec($sql);

        if($resultado > 0){
            return true;
        }else {
            return false;
        }
    }
    catch(PDOException $ex){
        echo 'Error en la conexión' . $ex->getMessage();
        return;
    }
}

?>