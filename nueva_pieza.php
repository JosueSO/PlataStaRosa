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
    <form method="POST" id="form1" action="">
    <div class="cont">
        <div class="buttons">
            <button class="toR" name="Insertar" type="submit" form="form1" value="Submit">Insertar</button>
        </div>
        <!--form method="POST" id="form1" action=""-->
        <div class="inputs">
            Codigo <input type="text" name="Codigo"/>
            <br>Familia <select name="Familia">
                        <?php
                            MakeConnection();
                           Tabla("Familia");
                        ?>
                        </select>
            <br>Categoría <select name="Categoria">
                        <option>Caucho</option>
                        <option>Pasta</option>
                        <option>Plata</option>
                        <option>Zirconia</option>
                        <?php
                           //MakeConnection();
                           //Tabla("Categoria");
                        ?>
                        </select>
            <br>
        </div>
        <div class="inputs">
            Precio <input type="number" name="Precio" />
            <br>Existencia <input type="number" name="Existencia" />
            <br> Proveedor <select name="Proveedor">
                        <?php
                            MakeConnection();
                            Tabla("Proveedor");
                        ?>
                    </select>
            <br>
        </div>
</form>
    </div>
</body>
</html>

<?php
$conexion;
$codigo = "";
$familia = "";
if($_SERVER['REQUEST_METHOD']=='POST'){
    MakeConnection();
    piezas("Pieza");
}
function MakeConnection(){
    $user = "postgres";
    $password = "Alijonas_963";
    $dbname = "central";
    $port = "5432";
    $host = "localhost";
    global $conexion;
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
    $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
}


function piezas($table){
    global $conexion;
    $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
    $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
    $fila=pg_fetch_array($resultado);
    echo $fila[1];
    if( $_POST['Categoria']== "Zirconia"){
        piezaS1($fila[1]);
    }
    else{
        piezaS2($fila[1]);
    }
}

function buscatabla($table){
    //MakeConnection();
    global $conexion;
    $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
    $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
    
    while($fila=pg_fetch_array($resultado)){
        if($_POST['flag'] == '')
            insert($fila[1]);
        else
            update($fila[1]);
    }
}

function Tabla($table){
    //MakeConnection();
    global $conexion;
    $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
    $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
    
    while($fila=pg_fetch_array($resultado)){
        if($table == "Proveedor")
           { llenaCombo($fila[1]);}
        elseif ($table=="Familia")
            {llenaComboFamilia($fila[1]);}
            elseif($table=="Categoria")
           { llenaComboCategoria($fila[1]);}
        else{
            echo 'welllll';
        }
    }
}

    function piezaS1($table){
        $id;
        global $conexion;
        global $codigo;
        global $familia;
        $user = "postgres";
        $password = "Alijonas_963";
        $dbname = "sta_rosa";
        $port = "5432";
        $host = "localhost";
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        try{
            CategoriayFamilia();
            $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
            $query = "insert into \"$table\" VALUES ('".$_POST['Codigo']."','".$familia."','".$codigo."',".$_POST['Precio'].",".$_POST['Existencia'].")";
            echo $query;
            $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        }
        catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
        }
    }

    function piezaS2($table){
        global $codigo;
        global $familia;
        $usuario = "root";
        $fuente = "mysql:host=localhost;dbname=sta_rosa";
        try{
            CategoriayFamilia();
            $conexion = new PDO($fuente, $usuario);
            //echo 'Conexión establecida';
            
            $sql = "insert into $table values('".$_POST['Codigo']."','".$familia."','".$codigo."',".$_POST['Precio'].",".$_POST['Existencia'].")";
            echo $sql;
            $resultado = $conexion->query($sql);
        }
        catch(PDOException $ex){
                echo 'Error en la conexión' . $ex->getMessage();
        }
        
    }

    function CategoriayFamilia(){
        global $codigo;
        global $familia;

        $user = "postgres";
        $password = "Alijonas_963";
        $dbname = "sta_rosa";
        $port = "5432";
        $host = "localhost";
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
            $qry = "select nombre from \"$table\"";
            $resultado = pg_query($conexion, $qry) or die("Error en la Consulta SQL");
            
            var_dump($resultado);
            while($fila = pg_fetch_array($resultado)){
                for($i = 0; $i < count($fila)/2; $i++){
                   echo "<option>$fila[$i]</option>";
                }
            }
        
        if($_POST['Familia']=="Anillo"){
            $familia = "ANI";
        } elseif($_POST['Familia']=="Aretes"){
            $familia = "ARE";
        } elseif($_POST['Familia']=="Arracadas"){
            $familia = "ARR";
        
        } elseif($_POST['Familia']=="Cadena"){
            $familia = "CAD";
        } elseif($_POST['Familia']=="Dije"){
            $familia = "DIJ";
        } elseif($_POST['Familia']=="Juego"){
            $familia = "JUE";
        } elseif($_POST['Familia']=="Pulsera"){
            $familia = "PUL";
        }

        if($_POST['Categoria']=="Caucho"){
            $codigo = "CAU";
        } elseif($_POST['Categoria']=="Pasta"){
            $codigo = "PAST";
        } elseif($_POST['Categoria']=="Plata"){
            $codigo = "PLA";
        } elseif($_POST['Categoria']=="Zirconia"){
            $codigo = "ZIR";
        }
        var_dump($codigo);
    }

    function llenaCombo($table){
        $user = "postgres";
        $password = "Alijonas_963";
        $dbname = "sta_rosa";
        $port = "5432";
        $host = "localhost";
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
        var_dump($table);
    
        $qry = "select nombre from \"$table\"";
        $resultado = pg_query($conexion, $qry) or die("Error en la Consulta SQL");
        
        var_dump($resultado);
        while($fila = pg_fetch_array($resultado)){
            for($i = 0; $i < count($fila)/2; $i++){
               echo "<option>$fila[$i]</option>";
            }
        }
    }

    function llenaComboFamilia($table){
        $user = "postgres";
        $password = "Alijonas_963";
        $dbname = "sta_rosa";
        $port = "5432";
        $host = "localhost";
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
            $qry = "select nombre from \"$table\"";
            $resultado = pg_query($conexion, $qry) or die("Error en la Consulta SQL");
            
            var_dump($resultado);
            while($fila = pg_fetch_array($resultado)){
                for($i = 0; $i < count($fila)/2; $i++){
                   echo "<option>$fila[$i]</option>";
                }
            }
        }

    function llenaComboCategoria($table){
            $usuario = "root";
            $fuente = "mysql:host=localhost;dbname=sta_rosa";
            try{
            $conexion = new PDO($fuente, $usuario);
            $sql = "SELECT nombre FROM $table";
            $resultado = $conexion->query($sql);
            echo "<option>$resultado</option>";
            while(($fila = $resultado->fetch(PDO::FETCH_ASSOC)) != false){
                for($i = 0; $i < count($fila)/2; $i++){
                   echo "<option>$fila[$i]</option>";
                }
            }
            }
            catch(PDOException $ex){
            echo "<option>$table</option>";
                echo 'Error en la conexión' . $ex->getMessage();
            }
            }

?>