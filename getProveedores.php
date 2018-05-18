<?php

$conexion;
                        MakeConnection();
                        Tabla("Proveedor");

function MakeConnection(){
        $user = "postgres";
        $password = "Alijonas_963";
        $dbname = "central";
        $port = "5432";
        $host = "localhost";
        global $conexion;
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
        //echo "<h3>Conexion Exitosa PHP - PostgreSQL</h3><hr><br>";

        $query = "select * from \"Atributos\"";
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        //var_dump($resultado);
        $fila=pg_fetch_array($resultado);
        //echo $fila[1];
    }

function Tabla($table){
    //MakeConnection();
    global $conexion;
    $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
    $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
    
    while($fila=pg_fetch_array($resultado)){
        llenaCombo($fila[1]);
    }
}

function llenaCombo($table){
    $user = "postgres";
    $password = "Alijonas_963";
    $dbname = "sta_rosa";
    $port = "5432";
    $host = "localhost";
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
    $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
    //var_dump($table);

    $qry = "select nombre from \"$table\"";
    $resultado = pg_query($conexion, $qry) or die("Error en la Consulta SQL");
    
    //var_dump($resultado);
    while($fila = pg_fetch_array($resultado)){
        echo "<option>$fila[0]</option>";
    }
}

?>