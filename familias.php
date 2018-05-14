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
            <li><a class="active" href="piezas.php">Piezas</a></li>
            <li><a href="ventas.php">Ventas</a></li>
            <li><a href="apartados.php">Apartados</a></li>
            <li><a href="mayoristas.php">Mayoristas</a></li>
            <li><a href="empleados.php">Empleados</a></li>
            <li><a href="proveedores.php">Proveedores</a></li>
          </ul>
    </div>
    
    <form id="form1" method="POST" action="">
    
    <div id="miModal" class="modal">
        <div class="ticket">
            <div class="TtoR"><a href="#">X</a></div>
            <div class="buttons">
                <button id="butt" class="toR" type="submit" form="form1" value="Submit" data-dismiss="modal">Agregar</button>
                <?php
                    $conexion;
                    MakeConnection();
                    if($_SERVER['REQUEST_METHOD']=='POST'){
                        Familia("Familia");
                    }
                ?>
            </div>
            <div class="FamCat">
                Código <input id="codigoFam" type="text" name="codigo"/>
                <br>Nombre <input id="nombreFam" type="text" name="nombre" />
                <br>
            </div>
            <input type="text" id="Resultado" class="invisible" name="flag"/>
        </div>
    </div>
</form>
    <div class="cont">
        
            <div class="buttons">
                <a href="#miModal"><button class="toR" id="editar">Editar</button></a>
                <a href="#miModal"><button class="toR" id="nuevo">Nuevo</button></a>
            </div>
            <div class="grid">
                <table id="TablaFamilia">
                    <tr>Familia</tr>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>  
                    </tr>
                    <!-- <tr class="par">
                        <td>ANI</td>
                        <td>Anillo</td>
                    </tr> -->
                    <?php
                        FamCat("Familia");
                    ?>
                </table>
            </div>
        
    </div>
</body>
</html>

<?php
$conexion;

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

    function FamCat($table){
        //MakeConnection();
        global $conexion;
        $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        //var_dump($resultado);
        //$i = 0;
        while($fila=pg_fetch_array($resultado)){
                famycatS1($fila[1]);
        }
    }   

    function famycatS1($table){
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

            $i = 0;
            while($fila = pg_fetch_array($resultado)){
                if ($i % 2 != 0)
                    echo "<tr class='par'>";
                else
                    echo "<tr class='impar'>";
                    
                for($i = 0; $i < count($fila)/2; $i++){
                    echo "<td>".$fila[$i]."</td>";
                }
                echo "</tr>";
                $i++;
            }
        }
        catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
        }
    }


    function Familia($table){
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

    function insert($table){
        $user = "postgres";
        $password = "Alijonas_963";
        $dbname = "sta_rosa";
        $port = "5432";
        $host = "localhost";
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
            
        try{
            $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
            $query = "insert into \"$table\" VALUES ('".$_POST['codigo']."','".$_POST['nombre']."')";
                //echo $query;
            $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        }
        catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
        }
    }

    function update($table){
        $user = "postgres";
        $password = "Alijonas_963";
        $dbname = "sta_rosa";
        $port = "5432";
        $host = "localhost";
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
            
        try{
            $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
            $query = "UPDATE \"$table\" SET nombre='".$_POST['nombre']."' WHERE id_familia='".$_POST['flag']."'";
                //echo $query;
            $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        }
        catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
        }
    }
?>

<script>
    var ultimaFila = null;
    var colorOriginal;

    $('#TablaFamilia tr').on('click',function(){
        $('#Resultado').val($(this).find('td:first').html());
        $('#codigoFam').val($(this).find('td:first').html());
        $('#nombreFam').val($(this).find('td:nth-child(2)').html());
        

        if(ultimaFila != null) {
            ultimaFila.css("background-color", colorOriginal);
        }

        colorOriginal = $(this).find('td').css('background-color');
        $(this).find('td').css("background-color","red");
        ultimaFila = $(this).find('td');
    });

    $('#nuevo').on('click', function(){
        $('#butt').html('Agregar');
        $('#Resultado').val('');
        $('#codigoFam').val('');
        $('#nombreFam').val('');
    });

    $('#editar').on('click', function(){
        $('#butt').html('Editar');
    });
</script>