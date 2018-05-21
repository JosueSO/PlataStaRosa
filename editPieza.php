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
}

$id_Pieza = $_POST['id_Pieza'];
$familia;
$categoria;
$_POST['oldCat'];
$precio = $_POST['Precio'];
$unidades = $_POST['Unidades'];

CategoriayFamilia("Familia","Categoria");
Actualiza();

function CategoriayFamilia($tabFam, $tabCat){
        
    global $conexion;
    $query = "select * from \"Fragmentos\" WHERE tabla='$tabFam'";
    $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
    
    while($fila=pg_fetch_array($resultado)){
        claveFamilia($fila[1]);
    }

    $qry = "select * from \"Fragmentos\" WHERE tabla='$tabCat'";
    $result = pg_query($conexion, $qry) or die("Error en la Consulta SQL");
    
    while($fil=pg_fetch_array($result)){
        claveCateg($fil[1]);
    }
}

function claveFamilia($table){
    global $familia;

    $user = "postgres";
    $password = "Alijonas_963";
    $dbname = "sta_rosa";
    $port = "5432";
    $host = "localhost";
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
    $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
    $qry = "select * from \"$table\" where nombre = '".$_POST['Familia']."'";
    $resultado = pg_query($conexion, $qry) or die("Error en la Consulta SQL");
    
    if($fila = pg_fetch_array($resultado)){
        $familia = $fila['id_familia'];
    }

}

function claveCateg($table){
    global $categoria;
    $usuario = "root";
    $fuente = "mysql:host=localhost;dbname=sta_rosa";
    try{
        $conexion = new PDO($fuente, $usuario);
        //echo 'Conexión establecida';
            
        $sql = "SELECT * FROM $table WHERE nombre = '".$_POST['Categoria']."'";
        $resultado = $conexion->query($sql);

        if(($fila = $resultado->fetch(PDO::FETCH_NUM)) != false){
            $categoria = $fila[0];
        }
    } catch(PDOException $ex){
        echo 'Error en la conexión' . $ex->getMessage();
    }
}

function Actualiza(){
    global $categoria;

    global $conexion;
        
    $query = "select * from \"Fragmentos\" WHERE tabla='Pieza'";
    $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
    $fila=pg_fetch_array($resultado);
    
    if($categoria ==  $_POST['oldCat'])
    {
        echo "Se actualiza en el mismo sitio";
        if ($categoria == 'ZIR'){
            $fila=pg_fetch_array($resultado);
            piezaS2($fila[1],true);
        }else{
            piezaS1($fila[1],true);
        }
    }else if($_POST['oldCat'] == 'ZIR'){
        echo "Se borra en sitio 2 e inserta en sitio 1";
        piezaS1($fila[1],false);
        $fila=pg_fetch_array($resultado);
        eliminaS2($fila[1]);
        
    }else if($categoria == 'ZIR'){
        echo "Se borra en sitio 1 e inserta en sitio 2";
        eliminaS1($fila[1]);
        $fila=pg_fetch_array($resultado);
        piezaS2($fila[1],false);
    }else {
        echo "Actualiza igual";
        piezaS1($fila[1],true);
    }
}

function piezaS1($table, $flag){
    global $categoria;
    global $familia;
    global $id_Pieza;
    global $precio;
    global $unidades;
    $user = "postgres";
    $password = "Alijonas_963";
    $dbname = "sta_rosa";
    $port = "5432";
    $host = "localhost";
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
    
    try{
        $conexionpg = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
       
        if($flag){
            $query ="update \"$table\" set id_familia ='".$familia."', id_categoria='".$categoria."',precio =".$precio.",cantidad=".$unidades." WHERE id_pieza = '".$id_Pieza."'";
            //echo $query;
        }else{
            $query = "insert into \"$table\" VALUES ('".$id_Pieza."','".$familia."','".$categoria."',".$precio.",".$unidades.")";
        }
        //echo $query;
        $resultado = pg_query($conexionpg, $query) or die("Error en la Consulta SQL");
    }
    catch(PDOException $ex){
        echo 'Error en la conexión' . $ex->getMessage();
    }
}

function piezaS2($table, $flag){
    global $categoria;
    global $familia;
    global $id_Pieza;
    global $precio;
    global $unidades;
    $usuario = "root";
    $fuente = "mysql:host=localhost;dbname=sta_rosa";
    try{
        $conexion = new PDO($fuente, $usuario);
        //echo 'Conexión establecida';
        if($flag){
            $sql ="update $table set id_familia ='".$familia."', id_categoria='".$categoria."',precio =".$precio.",cantidad=".$unidades." WHERE id_pieza = '".$id_Pieza."'";
            //echo $query;
        }else{
            $sql = "insert into $table VALUES ('".$id_Pieza."','".$familia."','".$categoria."',".$precio.",".$unidades.")";
        }
        //echo $sql;
        $resultado = $conexion->query($sql);
        //echo 'llego aqui but no hace nada';
    }
    catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
    }
    
}

function eliminaS1($table){
    global $id_Pieza;
    $user = "postgres";
    $password = "Alijonas_963";
    $dbname = "sta_rosa";
    $port = "5432";
    $host = "localhost";
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
    
    try{
        $conexionpg = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
       
        $query = "DELETE FROM \"$table\" WHERE id_pieza = '".$id_Pieza."'";
        //echo $query;
        $resultado = pg_query($conexionpg, $query) or die("Error en la Consulta SQL");
    }
    catch(PDOException $ex){
        echo 'Error en la conexión' . $ex->getMessage();
    }
}

function eliminaS2($table){
    global $id_Pieza;
    $usuario = "root";
    $fuente = "mysql:host=localhost;dbname=sta_rosa";
    try{
        $conexion = new PDO($fuente, $usuario);
        //echo 'Conexión establecida';
        $sql = "DELETE FROM $table WHERE id_pieza = '".$id_Pieza."'";
        //echo $sql;
        $resultado = $conexion->query($sql);
        //echo 'llego aqui but no hace nada';
    }
    catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
    }
    
}
?>