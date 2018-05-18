<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Plata Sta. Rosa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
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
            <li><a href="factura.php">Compra</a></li>
          </ul>
    </div>
    <div class="cont">
        <form action="" method="POST" id="form1">
            <div class="buttons">
            <button class="toR" type="submit" form="form1" value="Submit">Insertar</button>
            </div>
            <div class="inputs">
                Nombres (s) <input type="text" name="nombre" id="nombre"/>
                <br>Apellidos <input type="text" name="apellidos" id="apellidos"/>
                <br>Telefono <input type="text" name="telefono" id="telefono"/>
                <br>Constraseña <input type="password" name="contrasena" id="contrasena" />
                <br>Sueldo <input type="number" name="sueldo" id="sueldo"/>
                <br>
            </div>
            <div class="inputs">
                Calle <input type="text" name="calle" id="calle"/>
                <br>Número <input type="text" name="numCasa" id="numCasa"/>
                <br>Colonia <input type="text" name="colonia" id="colonia"/>
                <br>Ciudad <input type="text" name="ciudad" id="ciudad"/>
                <br>
            </div>
        </form>
    </div>
</body>
</html>

<?php
$conexion;
    if($_SERVER['REQUEST_METHOD']=='POST'){
        MakeConnection();
        empleados('Empleado');
    }
    if($_SERVER['REQUEST_METHOD']=='GET'){
        if(isset($_GET['id'])){
        MakeConnection();
        empleadoLlenaCampos($_GET['id']);
        $r2 = $_GET['lastname'];
        echo $_GET['sueldo'] . $_GET['tel'];
        echo "<script> $('#nombre').val('".$_GET['name']."'); </script>";
        echo "<script> $('#apellidos').val('".$r2."'); </script>";
        echo "<script> $('#telefono').val(('".$_GET['tel']."'); </script>";
        echo "<script> $('#contrasena').val('".$_GET['sueldo']."'); </script>";
        echo "<script> $('#ciudad').val('".$_GET['contrasena']."'); </script>";
        }else{}
    }
    else{
        $_POST['flag'] = false;
    }
    
    function MakeConnection(){
        $user = "postgres"; //MI IP 148.224.56.31
        $password = "Alijonas_963";
        $dbname = "central";
        $port = "5432";
        $host = "localhost";
        global $conexion;
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
        //echo "<h3>Conexion Exitosa PHP - PostgreSQL</h3><hr><br>"
    }

    function empleadoLlenaCampos($cveEmpleado){
        $id;
        global $conexion;
        $query = "select * from \"Fragmentos\" WHERE tabla='Empleado'";
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        $fila=pg_fetch_array($resultado);
            if($fila[3] == 1){}
               // empleadoS1($fila[1], $val);
            else
           { insertaDireccion($fila[1]);}
        }

    function empleadoS1($table, $values){
        echo 'HOLI';
        $user = "postgres";
        $password = "Alijonas_963";
        $dbname = "sta_rosa";
        $port = "5432";
        $host = "localhost";
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        if(isset($_GET['id'])==false){
        try{
            echo 'YES';
            $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
            $query = "INSERT INTO \"$table\" (nombre,apellidos,telefono,sueldo,contrasena) VALUES $values";
            echo $query;
            $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        }
        catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
        }
    }
    else{
        try{
            echo $table;
            $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
            $query = "update \"$table\" set nombre='". $_POST['nombre'] ."',apellidos='".$_POST['apellidos']."',telefono='".$_POST['telefono']."',sueldo=".$_POST['sueldo'].",contrasena='".$_POST['contrasena']."' WHERE id_empleado =". $_GET['id']."";
            echo $query;
            $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        }
        catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
        }
    }
    }

    function empleadoS2($table, $values){
        $usuario = "root";
        $fuente = "mysql:host=localhost;dbname=sta_rosa";
        if(isset($_GET['id'])==false){
        try{
            $conexion = new PDO($fuente, $usuario);
            //echo 'Conexión establecida';
            
            $sql = "INSERT INTO $table (calle,numCasa,colonia,ciudad) VALUES $values";
            echo $sql;
            $resultado = $conexion->query($sql);
        }
        catch(PDOException $ex){
                echo 'Error en la conexión' . $ex->getMessage();
        }
    }
    else{
        $conexion = new PDO($fuente, $usuario);
        insertaDireccion($table);
        
        $sql = "UPDATE $table SET calle='".$_POST['calle']."', numCasa = '".$_POST['numCasa']."',colonia='".$_POST['colonia']."',ciudad='".$_POST['ciudad']."' WHERE id_empleado=".$_GET['id']."";
        echo $sql;
        $resultado = $conexion->query($sql);
    }
        
    }

    function insertaDireccion($table){
        $usuario = "root";
        $fuente = "mysql:host=localhost;dbname=sta_rosa";
        try{
            $conexion = new PDO($fuente, $usuario);
        //echo 'Conexión establecida';
            $s2;
            $sql = "SELECT * FROM $table WHERE id_empleado =". $_GET['id']."";
            $resultado = $conexion->query($sql);
            $i=0;
            while(($fila = $resultado->fetch(PDO::FETCH_ASSOC)) != false){
                $s2[] = $fila[$i];
                echo 'holi'.$fila[$i];
            }
            
        echo $s2[1];
        echo "<script> $('#calle').val(".$s2[1]."); </script>";
        echo "<script> $('#numCasa').val($s2[2]); </script>";
        echo "<script> $('#colonia').val($s2[3]); </script>";
        echo "<script> $('#ciudad').val($s2[4]); </script>";
        }
        catch(PDOException $ex){
                echo 'Error en la conexión' . $ex->getMessage();
        }
    }

    //Edicion
    function empleados($table){
        $id;
        global $conexion;
        $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        //var_dump($resultado);
        //$i = 0;
        while($fila=pg_fetch_array($resultado)){
            $id = $fila[0];
            $query2 = "select * from \"Atributos\" WHERE id_fragmento=" . $id;
            $resultado2 = pg_query($conexion, $query2) or die("Error en la Consulta SQL");
            //var_dump($resultado2);
            //echo $query2;
            $aux = 0;
            //$val = "(";
            $val = "('" . $_POST['nombre']."','".$_POST['apellidos']."','".$_POST['telefono']."',".$_POST['sueldo'].",'".$_POST['contrasena']."')";
            echo $val;
            $val2 = "('".$_POST['calle']."',".$_POST['numCasa'].",'".$_POST['colonia']."','".$_POST['ciudad']."')";
            echo $val2;
            $fila2=pg_fetch_array($resultado2);
            while($fila2=pg_fetch_array($resultado2)){
                /*if($aux==0){
                    $val = $val . $_POST["$fila2[1]"];
                    $aux=$aux+1;
                }
                else{
                $val = $val . "," . $_POST["$fila2[1]"];
                }*/
            }
            //$val = $val. ")";
            //echo $val;
            if($fila[3] == 1)
               empleadoS1($fila[1], $val);
            else
                empleadoS2($fila[1], $val2);
        }
    }
?>