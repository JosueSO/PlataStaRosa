<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Plata Sta. Rosa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="jquery-3.3.1.js"></script>
</head>
<body>
    <div id="logo">
        <img src="images/logoPlata.png"/>
    </div>
    <div class="navi">
        <ul>
            <li><a href="piezas.php">Piezas</a></li>
            <li><a href="ventas.php">Ventas</a></li>
            <li><a href="apartados.php">Apartados</a></li>
            <li><a href="mayoristas.php">Mayoristas</a></li>
            <li><a href="empleados.php">Empleados</a></li>
            <li><a href="proveedores.php">Proveedores</a></li>
          </ul>
    </div>
    <form method="POST" id="form1">
    <div class="cont">
        <div class="buttons">
            <button class="toR" type="submit" form="form1" value="Submit">Insertar</button>
        </div>
        <div class="inputs">
            No. Credencial <input type="text" id="R1" name="Credencial"/>
            <br>Nombre <input type="text" id="R2" name="Nombre" />
            <br>
        </div>
        <div class="inputs">
            Teléfono <input type="text" id="R3" name="Telefono" />
            <br>
        </div>
    </div>
    </form>
</body>
</html>

<?php

$conexionpg;
function MakeConnection(){
    global $conexionpg;
    $user = "postgres";
    $password = "Alijonas_963";
    $dbname = "central";
    $port = "5432";
    $host = "localhost";
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
    $conexionpg = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
    //echo "<h3>Conexion Exitosa PHP - PostgreSQL</h3><hr><br>";

    $query = "select * from \"Atributos\"";
    $resultado = pg_query($conexionpg, $query) or die("Error en la Consulta SQL");
    //var_dump($resultado);
    $fila=pg_fetch_array($resultado);
    //echo $fila[1];
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    MakeConnection();
    Tabla('Mayorista');
}

if($_SERVER['REQUEST_METHOD']=='GET'){
    $r1 = $_GET['id'];
    $r2 = "'".$_GET['name']."'";
    $r3 = $_GET['tel'];
    echo "<script> $('#R1').val(".$r1."); </script>";
    echo "<script> $('#R2').val($r2); </script>";
    echo "<script> $('#R3').val(".$r3."); </script>";

    $_POST['flag'] = true;
    if(isset($_GET['Eliminar'])){
        MakeConnection();
        eliminar('Mayorista',$r1);
    }
}
else{
    $_POST['flag'] = false;
}

function Tabla($table){
    //MakeConnection();
    global $conexionpg;
    $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
    echo $table;
    $resultado = pg_query($conexionpg, $query) or die("Error en la Consulta SQL");
    echo $resultado;
    $con=0;
    while($fila=pg_fetch_array($resultado)){
        if($con>0){
        mayorista1($fila[1]);
    }
        else{
        mayorista2($fila[1]);
        $con=$con+1;
        }
    }
}

function eliminar($table, $id){
    global $conexionpg;
    $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
    echo $table;
    $resultado = pg_query($conexionpg, $query) or die("Error en la Consulta SQL");
    echo $resultado;
    $con=0;
    while($fila=pg_fetch_array($resultado)){
        if($con>0){
        eliminaPG($fila[1],$id);
    }
        else{
        eliminaMSQL($fila[1],$id);
        $con=$con+1;
        }
    }
}

function mayorista2($table){
    $usuario = "root";
        $fuente = "mysql:host=localhost;dbname=sta_rosa";
        try{
            $conexion = new PDO($fuente, $usuario);
            //echo 'Conexión establecida';
            
            if ($_GET['id'] != '') {
                $sql = "update $table set nombre='".$_POST['Nombre']."', telefono='".$_POST['Telefono']."' WHERE id_mayorista=".$_POST['Credencial'];
                //echo $sql;
                $resultado = $conexion->query($sql);
            }
            else {
                $sql = "insert into $table values(" .$_POST['Credencial'].",'".$_POST['Nombre']."','".$_POST['Telefono']."')";
                //echo $sql;
                $resultado = $conexion->query($sql);
            }
            
        }
        catch(PDOException $ex){
                echo 'Error en la conexión' . $ex->getMessage();
        }
        
    }

    function mayorista1($table){
        $user = "postgres";
        $password = "Alijonas_963";
        $dbname = "sta_rosa";
        $port = "5432";
        $host = "localhost";
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
        try{
            if ($_GET['id'] != '') {
                    $sql = "update \"$table\" set nombre='".$_POST['Nombre']."', telefono='".$_POST['Telefono']."' WHERE id_mayorista=".$_POST['Credencial'];
                    //echo $sql;
                    $resultado = pg_query($conexion, $sql) or die("Error en la Consulta SQL");
            }    
            else{
            $sql = "insert into \"$table\" values(" .$_POST['Credencial'].",'".$_POST['Nombre']."','".$_POST['Telefono']."')";
            echo $sql;
            $resultado = pg_query($conexion, $sql) or die("Error en la Consulta SQL");
            }
            header('Location: mayoristas.php');
        }
        catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
        }
    }
    function eliminaPG($table, $id){
        $user = "postgres";
        $password = "Alijonas_963";
        $dbname = "sta_rosa";
        $port = "5432";
        $host = "localhost";
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
        try{
            
            $sql = "DELETE FROM \"$table\" WHERE id_mayorista =" .$id ;
            $resultado = pg_query($conexion, $sql) or die("Error en la Consulta SQL");
            header('Location: mayoristas.php');
        }
        catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
        }
    }

    function eliminaMSQL($table, $id){
        $usuario = "root";
        $fuente = "mysql:host=localhost;dbname=sta_rosa";
        try{
            $conexion = new PDO($fuente, $usuario);
            //echo 'Conexión establecida';
                $sql = "DELETE FROM $table WHERE id_mayorista =" .$id ;
                //echo $sql;
                $resultado = $conexion->query($sql);
                
            }
        catch(PDOException $ex){
                echo 'Error en la conexión' . $ex->getMessage();
        }
    }
?>