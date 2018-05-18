<?php
$conexionpg;

MakeConnection();

$id_apartado = $_POST['idApartado'];
$id_venta;

Apartados("Apartado");

function MakeConnection(){
    global $conexionpg;
    $user = "postgres";
    $password = "Alijonas_963";
    $dbname = "central";
    $port = "5432";
    $host = "localhost";
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
    $conexionpg = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
}

function GetTableName($t1){
    global $conexionpg;
    $query = "select * from \"Fragmentos\" WHERE tabla='$t1'";
    $resultado = pg_query($conexionpg, $query) or die("Error en la Consulta SQL");

    if($fila=pg_fetch_array($resultado)){
        return $fila[1];
    }
}

function Apartados($table){
    $tabla_apartados = GetTableName($table);
    consulta($tabla_apartados);

    $tabla_detalleA = GetTableName("DetalleApartado");
    detalle($tabla_detalleA);
} 

function consulta($tabla){
    $conexion;
    $user = "postgres";
    $password = "Alijonas_963";
    $dbname = "sta_rosa";
    $port = "5432";
    $host = "localhost";
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
    try{
        $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());

        global $id_apartado;
        $query = "select * from \"$tabla\" where id_apartado = ".$id_apartado;
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
    
        if($fila = pg_fetch_array($resultado)){
            insertaVenta($fila);
        }

        $query = "DELETE FROM \"$tabla\" WHERE id_apartado = ".$id_apartado;
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
    }
    catch(PDOException $ex){
        echo 'Error en la conexión' . $ex->getMessage();
    }
}

function insertaVenta($apartado){
    $usuario = "root";
    $fuente = "mysql:host=localhost;dbname=sta_rosa";
    $table = GetTableName("Ventas");
    try{
        $conexion = new PDO($fuente, $usuario);
        $sql = "INSERT INTO $table(id_empleado, fecha, subtotal, descuento, total) VALUES($apartado[1],SYSDATE(),$apartado[5],0,$apartado[5])";
        //echo $sql;
        $resultado = $conexion->query($sql);

        $sql = "SELECT id_venta FROM $table ORDER BY id_venta DESC LIMIT 1";

        $resultado = $conexion->query($sql);

        if(($fila = $resultado->fetch(PDO::FETCH_NUM)) != false){
            global $id_venta;
            $id_venta = $fila[0];
        } 
    }
    catch(PDOException $ex){
        echo 'Error en la conexión' . $ex->getMessage();
    }
}

function detalle($table1){
    $conexion;
    $user = "postgres";
    $password = "Alijonas_963";
    $dbname = "sta_rosa";
    $port = "5432";
    $host = "localhost";
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
    try{
        $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());

        global $id_apartado;
        $query = "select * from \"$table1\" where id_apartado = ".$id_apartado;
        //echo $query;
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
    
        while($fila = pg_fetch_array($resultado)){
            insertaDetalle($fila[1],$fila[2]);
        }

        $query = "DELETE FROM \"$table1\" WHERE id_apartado = ".$id_apartado;
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
    }
    catch(PDOException $ex){
        echo 'Error en la conexión' . $ex->getMessage();
    }
}

function insertaDetalle($pieza, $cantidad){
    $usuario = "root";
    $fuente = "mysql:host=localhost;dbname=sta_rosa";
    $table = GetTableName("DetalleVenta");
    try{
        $conexion = new PDO($fuente, $usuario);

        global $id_venta;
        $sql = "INSERT INTO $table VALUES($id_venta,'$pieza',$cantidad)";
        //echo $sql;
        $resultado = $conexion->query($sql);
    }
    catch(PDOException $ex){
        echo 'Error en la conexión' . $ex->getMessage();
    }
}