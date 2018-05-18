<?php
$conexion;

$id_Pieza = $_POST['idPieza'];
$cantidad = $_POST['cantidad'];

echo "<tr>";
MakeConnection();
echo "</tr>";

function MakeConnection(){
    global $conexion;
    $user = "postgres";
    $password = "Alijonas_963";
    $dbname = "central";
    $port = "5432";
    $host = "localhost";
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
    $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());

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
        $query = "SELECT * FROM \"$table\" WHERE id_pieza = '".$id_Pieza."'";

        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");

        if($fila = pg_fetch_array($resultado)){
            $total = $cantidad * $fila[3];
            echo "<td>".$cantidad."</td>";
            echo "<td class='TtoC'>".$id_Pieza."</td>";
            echo "<td> $".$total."</td>";
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
        $sql = "SELECT * FROM $table WHERE id_pieza = '".$id_Pieza."'";
        //echo $sql;
        $resultado = $conexion->query($sql);
        if(($fila = $resultado->fetch(PDO::FETCH_NUM)) != false){
            $total = $cantidad * $fila[3];
            echo "<td>".$cantidad."</td>";
            echo "<td class='TtoC'>".$id_Pieza."</td>";
            echo "<td> $".$total."</td>";
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