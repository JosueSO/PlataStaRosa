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
            <li><a class="active" href="">Ventas</a></li>
            <li><a href="apartados.php">Apartados</a></li>
            <li><a href="mayoristas.php">Mayoristas</a></li>
            <li><a href="empleados.php">Empleados</a></li>
            <li><a href="proveedores.php">Proveedores</a></li>
          </ul>
    </div>
    <!--form id="form2"-->
    <div id="miModal" class="modal">
        <div class="ticket">
            <div>
                <div>Venta #1</div>
                <div class="closeB"><a href="#">X</a></div>
            </div>
            <div class="TtoR">
                2018/04/01
            </div>
            <table class="listP">
                <tr>
                    <th>Pieza</th>
                    <th>Precio</th>
                </tr>
                <tr>
                    <td>ANIZIR250</td>
                    <td class="TtoC">$250</td>
                </tr>
                <tr>
                    <td>AREPAS150</td>
                    <td class="TtoC">$150</td>
                </tr>
                <tr class="blankspace">
                    <td ></td>
                    <td ></td>
                </tr>
                <tr>
                    <td class="TtoR">Subtotal</td>
                    <td class="TtoC">$400</td>
                </tr>
                <tr>
                    <td class="TtoR">Descuento</td>
                    <td class="TtoC">10%</td>
                </tr>
                <tr>
                    <td class="TtoR">Total</td>
                    <td class="TtoC">$360</td>
                </tr>
            </table>
            
            <div>
                ATENDIÓ: Alicia
            </div>
        </div>
    </div>
    <!--/form-->
    <form method="POST" id="form1">
    <input type="text" id="Resultado" name="RES"/>
    <div class="cont">
        <div class="buttons">
            <button class="toR" type="submit" form="form1" value="Submit" name="Eliminar">Eliminar</button>
            <a href="#miModal"><button class="toR" form="form2" id="detail">Detalle</button></a>
        </div>
        <div class="grid">
            <table id="TablaVentas">
                <tr>
                    <th>Folio</th>
                    <th>Empleado</th>
                    <th>Mayorista</th>
                    <th>Fecha</th>
                    <th>Subtotal</th> 
                    <th>Descuento</th>
                    <th>Total</th>
                    
                </tr>
                <?php

                    $conexion;
                    MakeConnection();
                    Tabla("Ventas");
                ?>
            </table>
        </div>
    </div>
    </form>
</body>
</html>

<?php
if($_SERVER['REQUEST_METHOD']=='POST'){
    echo $_POST['RES'];
    eliminar('Ventas',$_POST['RES']);
    header("Location: ventas.php");
}

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
function Tabla($table){
    global $conexionpg;
    $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
    //echo $table;
    $resultado = pg_query($conexionpg, $query) or die("Error en la Consulta SQL");
    //echo $resultado;
    $fila=pg_fetch_array($resultado);
    ventas($fila[1]);
}

function ventas($table){
    $usuario = "root";
    $fuente = "mysql:host=localhost;dbname=sta_rosa";
    try{
        $conexion = new PDO($fuente, $usuario);
        //echo 'Conexión establecida';
        
        $sql = "SELECT * FROM $table";
        $resultado = $conexion->query($sql);
        while(($fila = $resultado->fetch(PDO::FETCH_NUM)) != false){
            echo "<tr class='par'>";
            foreach($fila as $elemento){
                echo "<td>".$elemento."</td>";
            }
            echo "</tr>";
        }
    }
    catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
    }
    
}

function eliminar($table, $id){
    global $conexionpg;
    $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
    echo $table;
    $resultado = pg_query($conexionpg, $query) or die("Error en la Consulta SQL");
    echo $resultado;
    $fila=pg_fetch_array($resultado);
    eliminaMSQL($fila[1],$id);
}

function eliminaMSQL($table, $id){
    $usuario = "root";
    $fuente = "mysql:host=localhost;dbname=sta_rosa";
    try{
        $conexion = new PDO($fuente, $usuario);
        //echo 'Conexión establecida';
            $sql = "DELETE FROM $table WHERE id_venta =" .$id ;
            //echo $sql;
            $resultado = $conexion->query($sql);
            
        }
    catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
    }
}
?>

<script>
    var ultimaFila = null;
    var colorOriginal;

    $('#TablaVentas tr').on('click',function(){
        var dato = $(this).find('td:first').html();
        $('#Resultado').val(dato);

        if(ultimaFila != null) {
            ultimaFila.css("background-color", colorOriginal);
        }

        colorOriginal = $(this).find('td').css('background-color');
        $(this).find('td').css("background-color","red");
        ultimaFila = $(this).find('td');
    });
</script>