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
            <li><a class="active" href="">Piezas</a></li>
            <li><a href="ventas.php">Ventas</a></li>
            <li><a href="apartados.php">Apartados</a></li>
            <li><a href="mayoristas.php">Mayoristas</a></li>
            <li><a href="empleados.php">Empleados</a></li>
            <li><a href="proveedores.php">Proveedores</a></li>
            <li><a href="facturas.php">Factura</a></li>
          </ul>
    </div>
    <form action="nueva_pieza.php" method="GET" id="form1">
    <div class="cont">
        <div class="buttons">
            <a href="familias.php"><button class="toL" form="form2">Familias</button></a>
            <a href="categorias.php"><button class="toL" form="form2">Categorias</button></a>
            <button class="toR" type="submit" value="Eliminar" name="Eliminar">Eliminar</button>
            <button class="toR" type="submit" form="form1" value="Submit">Editar</button>
            <a href="nueva_pieza.php"><button class="toR" form="form3">Nuevo</button></a>
        </div>
        <div class="grid">
            <table id="TablaPiezas">
                <tr>
                    <th>Código</th>
                    <th>Familia</th> 
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Existencia</th>  
                </tr>
                <?php
                    $conexion;
                    MakeConnection();
                    piezas();
                ?>
                <!-- <tr class="par">
                    <td>ANIZIR225</td>
                    <td>Anillo</td> 
                    <td>Zirconia</td>
                    <td class="TtoR">$225</td>
                    <td class="TtoC">20</td>
                </tr> -->
                
            </table>
            <input type="text" id="R1" name="id"/>
            <input type="text" id="R2" name="familia"/>
            <input type="text" id="R3" name="categoria"/>
            <input type="text" id="R4" name="precio"/>
            <input type="text" id="R5" name="unidades"/>
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

    function piezas(){
        //MakeConnection();
        global $conexion;
        $query = "select * from \"Fragmentos\" WHERE tabla='Pieza'";
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        //var_dump($resultado);
        //$i = 0;
        while($fila=pg_fetch_array($resultado)){
            if($fila[3] == 1)
                piezasS1($fila[1]);
            else
                piezasS2($fila[1]);
            //$i++;
            /*<tr class="par">
                    <td>ANIZIR225</td>
                    <td>Anillo</td> 
                    <td>Zirconia</td>
                    <td class="TtoR">$225</td>
                    <td class="TtoC">20</td>
                </tr>*/
        }
    }

    function piezasS1($table){
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

    function piezasS2($table){
        $usuario = "root";
        $fuente = "mysql:host=localhost;dbname=sta_rosa";
        try{
            $conexion = new PDO($fuente, $usuario);
            //echo 'Conexión establecida';
            
            $sql = "SELECT * FROM $table";
            $resultado = $conexion->query($sql);

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
        }
        catch(PDOException $ex){
                echo 'Error en la conexión' . $ex->getMessage();
        }
        
    }


?>

<script>
    var ultimaFila = null;
    var colorOriginal;

    $('#TablaPiezas tr').on('click',function(){
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
        $('#R5').val($(this).find('td:nth-child(5)').html());
    });
</script>