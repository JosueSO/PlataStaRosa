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
          </ul>
    </div>
    <div class="cont">
        <div class="buttons">
            <button class="toR" type="submit" form="form1" value="Submit">Insertar</button>
        </div>
        <form method="POST" id="form1" action="">
        <div class="inputs">
            Nombre <input type="text" name="nombre"/>
            <br>Telefono <input type="text" name="telefono" />
            <br>Calle <input type="text" name="calle"/>
            <br>
        </div>
        <div class="inputs">
            Número <input type="number" name="numCasa"/>
            <br>Colonia <input type="text" name="colonia" />
            <br>Ciudad <input type="text" name="ciudad" />
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
        proveedores();
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

    function proveedores(){
        $id;
        global $conexion;
        $query = "select * from \"Fragmentos\" WHERE tabla='Proveedor'";
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        //var_dump($resultado);
        //$i = 0;
        while($fila=pg_fetch_array($resultado)){
            $id = $fila[0];
            $query2 = "select * from \"Atributos\" WHERE id_fragmento=" . $id ;
            $resultado2 = pg_query($conexion, $query2) or die("Error en la Consulta SQL");
            //var_dump($resultado2);
            echo $query2;
            $aux = 0;
            //$val = "(";
            $val = "('". $_POST['nombre']."','".$_POST['telefono']."')" ;
            echo $val;
            $val2 = "('".$_POST['calle']."',".$_POST['numCasa'].",'".$_POST['colonia']."','".$_POST['ciudad']."')";
            echo $val2;
            $fila2=pg_fetch_array($resultado2);
           while($fila2=pg_fetch_array($resultado2)){
               /* if($aux==0){
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
                provS1($fila[1], $val);
            else
                provS2($fila[1], $val2);
        }
    }

    function provS1($table, $values){
        $user = "postgres";
        $password = "Alijonas_963";
        $dbname = "sta_rosa";
        $port = "5432";
        $host = "localhost";
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        try{
            $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
            $query = "INSERT INTO \"$table\" (nombre,telefono) VALUES $values";
            echo $query;
            $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        }
        catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
        }
    }

    function provS2($table, $values){
        $usuario = "root";
        $fuente = "mysql:host=localhost;dbname=sta_rosa";
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
?>