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
            <li><a class="active">Proveedores</a></li>
          </ul>
    </div>
    <form method="POST" id="form2">
    <input type="text" id="Resultado" name="RES"/>
    <div class="cont">
        <div class="buttons">
            <button class="toR" type="submit" value="Eliminar" name="Eliminar">Eliminar</button>
</form>
<form action="nuevo_proveedor.php" method="GET" id="form1">
            <button class="toR" type="submit" form="form1" value="Submit">Editar</button>
            <a href="nuevo_proveedor.php"><button class="toR" form="form3">Nuevo</button></a>
        </div>
        <div class="grid">
            <table id="TablaProvedores">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Domicilio</th>
                </tr>
                <?php
                    $s1 = array();
                    $s2 = array();
                    MakeConnection();
                    Proveedores();
                ?>
            </table>
            <input type="text" id="R1" name="id"/>
            <input type="text" id="R2" name="nombre"/>
            <input type="text" id="R3" name="telefono"/>
            <input type="text" id="R4" name="domicilio"/>
        </div>
    </div>
    </form>
</body>
</html>

<?php

if($_SERVER['REQUEST_METHOD']=='POST'){
    echo $_POST['RES'];
    MakeConnection();
    eliminar('Proveedor',$_POST['RES']);
    //header("Location: apartados.php");
}

function eliminar($table, $id){
    global $conexion;
    $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
    echo $table;
    $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
    echo $resultado;
    $con=0;
    while($fila=pg_fetch_array($resultado)){
        if($con==0){
        eliminaPG($fila[1],$id);
        $con=$con+1;
    }
        else{
        eliminaMSQL($fila[1],$id);
        $con=$con+1;
        }
    }
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

        $query = "select * from \"Atributos\"";
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        $fila=pg_fetch_array($resultado);
    }

    function Proveedores(){
        global $conexion;
        $query = "select * from \"Fragmentos\" WHERE tabla='Proveedor'";
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        //var_dump($resultado);
        //$i = 0;
        while($fila=pg_fetch_array($resultado)){
            if($fila[3] == 1)
                ProvS1($fila[1]);
            else
                ProvS2($fila[1]);
        }
        global $s1;
        global $s2;
        
        joinProv();
        
        for($i=0; $i < count($s1); $i++){
            if ($i % 2 != 0)
                echo "<tr class='par'>";
            else
                echo "<tr class='impar'>";
                
            for($j = 0; $j < count($s1[$i]); $j++){
                echo "<td>".$s1[$i][$j]."</td>";
            }
            echo "</tr>";
        }
    }

    function ProvS1($table){
        $user = "postgres";
        $password = "Alijonas_963";
        $dbname = "sta_rosa";
        $port = "5432";
        $host = "localhost";
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        try{
            $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
            $query = "select * from \"$table\"";
            $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");

            global $s1;
            $cont = 0;
            while($fila = pg_fetch_array($resultado)){
                $s1[$cont] = array(); 
                for($i = 0; $i < count($fila)/2; $i++){
                    $s1[$cont][] = $fila[$i];
                }
                $cont++;
            }
        }
        catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
    }
    }

    function ProvS2($table){
        $usuario = "root";
        $fuente = "mysql:host=localhost;dbname=sta_rosa";
        try{
            $conexion = new PDO($fuente, $usuario);
            global $s2;
            $sql = "SELECT * FROM $table";
            $resultado = $conexion->query($sql);
            while(($fila = $resultado->fetch(PDO::FETCH_NUM)) != false){
                $s2[] = $fila;
            }
        }
        catch(PDOException $ex){
                echo 'Error en la conexión' . $ex->getMessage();
        }
        
    }

    function joinProv(){
        global $s1;
        global $s2;

        //var_dump($s1);
        //var_dump($s2);
        
        for($i=0; $i < count($s1); $i++){
            $s1[$i][3] = "";
            for($j = 0; $j < count($s2); $j++){
                if($s1[$i][0]==$s2[$j][0]){
                    for($c=1; $c < count($s2[$j]); $c++){
                        $s1[$i][3] = $s1[$i][3] . " " . $s2[$j][$c];
                    }
                }
            }
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
            
            $sql = "DELETE FROM \"$table\" WHERE id_proveedor =" .$id ;
            $resultado = pg_query($conexion, $sql) or die("Error en la Consulta SQL");
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
                $sql = "DELETE FROM $table WHERE id_proveedor =" .$id ;
                //echo $sql;
                $resultado = $conexion->query($sql);
                header('Location: proveedores.php');
            }
        catch(PDOException $ex){
                echo 'Error en la conexión' . $ex->getMessage();
        }
    }
?>

<script>
    var ultimaFila = null;
    var colorOriginal;

    $('#TablaProvedores tr').on('click',function(){
        var dato = $(this).find('td:first').html();
        $('#Resultado').val(dato);

        if(ultimaFila != null) {
            ultimaFila.css("background-color", colorOriginal);
        }

        colorOriginal = $(this).find('td').css('background-color');
        $(this).find('td').css("background-color","red");
        ultimaFila = $(this).find('td');

        $('#R1').val($(this).find('td:first').html()); 
        $('#R2').val($(this).find('td:nth-child(2)').html());
        $('#R3').val($(this).find('td:nth-child(3)').html());
        $('#R4').val($(this).find('td:nth-child(4)').html());
    });
</script>