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
            <li><a class="active">Mayoristas</a></li>
            <li><a href="empleados.php">Empleados</a></li>
            <li><a href="proveedores.php">Proveedores</a></li>
            <li><a href="facturas.php">Factura</a></li>
          </ul>
    </div>
    <form action="nueva_mayorista.php" method="GET" id="form1">
    <div class="cont">
        <div class="buttons">
            <button class="toR" type="submit" value="Eliminar" name="Eliminar">Eliminar</button>
            <button class="toR" type="submit" form="form1" value="Submit">Editar</button>
            <a href="nueva_mayorista.php"><button class="toR">Nuevo</button></a>
        </div>
        <div class="grid">
            <table id="TablaMayoristas">
                <tr>
                    <th>No. Credencial</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                </tr>
                <?php
                $conexion;
                MakeConnection();
                Mayoristas();
                ?>
            </table>
            <input type="text" id="R1" name="id"/>
            <input type="text" id="R2" name="name"/>
            <input type="text" id="R3" name="tel"/>
        </div>
    </div>
    </form>
</body>
</html>

<?php

function MakeConnection(){
    $user = "postgres"; //MI IP 148.224.56.31
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

function Mayoristas(){
    //MakeConnection();
    global $conexion;
    $query = "select * from \"Fragmentos\" WHERE tabla='Mayorista'";
    $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
    //var_dump($resultado);
    //$i = 0;
    while($fila=pg_fetch_array($resultado)){
        if($fila[3] == 1){
            if (empleadoS1($fila[1]) == true)
                break;
        }
        else{
            if(empleadoS2($fila[1]) == true)
                break;
        }
        
    }
}

function empleadoS2($table){
    $usuario = "root";
    $fuente = "mysql:host=localhost;dbname=sta_rosa";
    try{
        $conexion = new PDO($fuente, $usuario);
        //echo 'Conexión establecida';
        
        $sql = "SELECT * FROM $table";
        $resultado = $conexion->query($sql);
        //echo $sql;
        $i = 0;
        while(($fila = $resultado->fetch(PDO::FETCH_NUM)) != false){
            if ($i % 2 != 0)
                echo "<tr class='par'>";
            else
                echo "<tr class='impar'>";
                
            foreach($fila as $elemento){
                echo "<td>".$elemento."</td>";
            }
            echo "</tr>";

            $i++;
        }

        if ($i > 0)
            return true;
        else
            return false;
    }
    catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
    }
    
}

function empleadoS1($table){
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
            if ($cont % 2 != 0)
                echo "<tr class='par'>";
            else
                echo "<tr class='impar'>";
                
            for($i = 0; $i < count($fila)/2; $i++){
                echo "<td>".$fila[$i]."</td>";
            }
            echo "</tr>";
            $cont++;
        }

        if ($cont > 0)
            return true;
        else
            return false;
    }
    catch(PDOException $ex){
        echo 'Error en la conexión' . $ex->getMessage();
}
}
?>

<script>
    var ultimaFila = null;
    var colorOriginal;

    $('#TablaMayoristas tr').on('click',function(){
        //var dato = $(this).find('td:first').html();
        //$('#Resultado').val(dato);

        if(ultimaFila != null) {
            ultimaFila.css("background-color", colorOriginal);
        }

        colorOriginal = $(this).find('td').css('background-color');
        $(this).find('td').css("background-color","red");
        ultimaFila = $(this).find('td');

        $('#R1').val($(this).find('td:first').html()); 
        $('#R2').val($(this).find('td:nth-child(2)').html());
        $('#R3').val($(this).find('td:nth-child(3)').html());
    });
</script>