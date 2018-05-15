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
    <form method="POST" id="form1" action="">
    <div class="cont">
        <div class="buttons">
            <button class="toR" name="Insertar" type="submit" form="form1" value="Submit">Insertar</button>
        </div>
        <!--form method="POST" id="form1" action=""-->
        <?php 
            $conexion;
            $codigo = "";
            $familia = "";
            $prov;
            MakeConnection();
            if($_SERVER['REQUEST_METHOD']=='POST'){
                //echo "Aqui si funciona";
                piezas("Pieza");
                suministra("SuministraPieza");
                //echo "Aqui tambien";
            }
        ?>
        <div class="inputs">
            Codigo <input id="R1" type="text" name="Codigo"/>
            <br>Familia <select id="R2" name="Familia">
                        <?php
                            Tabla("Familia");
                        ?>
                        </select>
            <br>Categoría <select id="R3" name="Categoria">
                        <!-- <option>Caucho</option>
                        <option>Pasta</option>
                        <option>Plata</option>
                        <option>Zirconia</option> -->
                        <?php
                          // MakeConnection();
                            Tabla("Categoria");
                        ?>
                        </select>
            <br>
        </div>
        <div class="inputs">
            Precio <input id="R4" type="number" name="Precio" />
            <br>Existencia <input id="R5" type="number" name="Existencia" />
            <br> Proveedor <select name="Proveedor">
                        <?php
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
    if($_SERVER['REQUEST_METHOD']=='GET'){
        $r1 = "'".$_GET['id']."'";
        $r2 = "'".$_GET['familia']."'";
        $r3 = "'".$_GET['categoria']."'";
        $r4 = $_GET['precio'];
        $r5 = $_GET['unidades'];
        echo $r1;
        echo "<script> $('#R1').val($r1); </script>";
        echo "<script> $('#R4').val(".$r4."); </script>";
        echo "<script> $('#R5').val(".$r5."); </script>";
    
        $_POST['flag'] = true;
        //if(isset($_GET['Eliminar'])){
        //    MakeConnection();
        //    eliminar('Mayorista',$r1);
        //}
    }
    else{
        $_POST['flag'] = false;
        echo "hola";
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

    function suministra($table){
        global $conexion;
        $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        $fila=pg_fetch_array($resultado);

        if($fila[3] == 1){
            sum1($fila[1]);
        }
        else {
            sum2($fila[1]);
        }
    }

    function sum1($table){
        $user = "postgres";
        $password = "Alijonas_963";
        $dbname = "sta_rosa";
        $port = "5432";
        $host = "localhost";
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        Proveedor("Proveedor");
        global $prov;
        try{
            $conexionpg = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
            $total = $_POST['Existencia'] * $_POST['Precio'];
            $query = "insert into \"$table\" VALUES (".$prov.",'".$_POST['Codigo']."',".$_POST['Existencia'].",".$_POST['Precio'].",".$total.", current_date)";
            echo $query;
            //var_dump($query);
            $resultado = pg_query($conexionpg, $query) or die("Error en la Consulta SQL");
        }
        catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
        }
    }

    function sum2($table){
        global $prov;
        $usuario = "root";
        $fuente = "mysql:host=localhost;dbname=sta_rosa";
        Proveedor("Proveedor");
        try{
            $conexion = new PDO($fuente, $usuario);
            $total = $_POST['Existencia'] * $_POST['Precio'];
            $sql = "INSERT INTO ".$table." VALUES (".$prov.",'".$_POST['Codigo']."',".$_POST['Existencia'].",".$_POST['Precio'].",".$total.", CURRENT_DATE)";
            echo $sql;
            $resultado = $conexion->query($sql);
        }
        catch(PDOException $ex){
                echo 'Error en la conexión' . $ex->getMessage();
        }
        
    }

    function Proveedor($table){
        global $conexion;
        $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        $fila=pg_fetch_array($resultado);

        if($fila[3] == 1){
            prov1($fila[1]);
        }
        else {
            prov2($fila[1]);
        }
    }

    function prov1($table){
        $user = "postgres";
        $password = "Alijonas_963";
        $dbname = "sta_rosa";
        $port = "5432";
        $host = "localhost";
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        global $prov;
        try{
            $conexionpg = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
            $total = $_POST['Existencia'] * $_POST['Precio'];
            $query = "select * from \"".$table."\" WHERE nombre = '".$_POST['Proveedor']."'";
            $resultado = pg_query($conexionpg, $query) or die("Error en la Consulta SQL");
            $fila=pg_fetch_array($resultado);

            $prov = $fila[0];
        }
        catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
        }
    }

    function prov2($table){
        $usuario = "root";
        $fuente = "mysql:host=localhost;dbname=sta_rosa";
        global $prov;
        try{
            $conexion = new PDO($fuente, $usuario);
            //echo 'Conexión establecida';
                
            $query = "select * from ".$table." WHERE nombre = '".$_POST['Proveedor']."'";
            $resultado = $conexion->query($sql);
    
            if(($fila = $resultado->fetch(PDO::FETCH_NUM)) != false){
                $prov = $fila[0];
            }
        } catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
        }
    }

    function piezas($table){
        global $conexion;
        $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        $fila=pg_fetch_array($resultado);
        //echo $fila[1];
        if( $_POST['Categoria'] != "Zirconia"){
            piezaS1($fila[1]);
        }
        else{
            $fila=pg_fetch_array($resultado);
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
        $conexionpg;
        global $codigo;
        global $familia;
        $user = "postgres";
        $password = "Alijonas_963";
        $dbname = "sta_rosa";
        $port = "5432";
        $host = "localhost";
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        CategoriayFamilia('Familia','Categoria');
        try{
            
            $conexionpg = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
            $query = "insert into \"$table\" VALUES ('".$_POST['Codigo']."','".$familia."','".$codigo."',".$_POST['Precio'].",".$_POST['Existencia'].")";
            echo $query;
            $resultado = pg_query($conexionpg, $query) or die("Error en la Consulta SQL");
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
            CategoriayFamilia('Familia','Categoria');
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

        /*if($_POST['Familia']=="Anillo"){
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
        var_dump($codigo);*/
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
        
        var_dump($resultado);
        if($fila = pg_fetch_array($resultado)){
            $familia = $fila['id_familia'];
        }

    }

    function claveCateg($table){
        global $codigo;
        $usuario = "root";
        $fuente = "mysql:host=localhost;dbname=sta_rosa";
        try{
            $conexion = new PDO($fuente, $usuario);
            //echo 'Conexión establecida';
                
            $sql = "SELECT * FROM $table WHERE nombre = '".$_POST['Categoria']."'";
            $resultado = $conexion->query($sql);
    
            if(($fila = $resultado->fetch(PDO::FETCH_NUM)) != false){
                $codigo = $fila[0];
            }
        } catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
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
            //echo "<option>$resultado</option>";
            echo "hola";
            while(($fila = $resultado->fetch(PDO::FETCH_NUM)) != false){
                for($i = 0; $i < count($fila)/2; $i++){
                   echo "<option>".$fila[$i]."</option>";
                }
            }
        }
        catch(PDOException $ex){
            echo "<option>$table</option>";
            echo 'Error en la conexión' . $ex->getMessage();
        }
    }

?>