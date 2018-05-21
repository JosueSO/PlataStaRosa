<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Plata Sta. Rosa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="jquery-3.3.1.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
            <li><a class="active" href="facturas.php">Facturas</a></li>
          </ul>
    </div>
    <!--form id="form2"-->
    <div id="miModal" class="modal">
        <div class="ticket">
            <div>
                <div id="idSum"></div>
                <div class="closeB"><a href="#">X</a></div>
            </div>
            <div class="TtoR" id="fechaSum"></div>
            <div class="TtoR" id="proveedorSum">
            </div>
            <!-- <table class="listP"> -->
                <!-- <tr>
                    <td>AREPAS150</td>
                    <td class="TtoC">$150</td>
                </tr> -->
                <span id="piezasSum">
                <script type='text/javascript'>
                    $('#idSum').on('DOMSubtreeModified', function() {
                        if ($('#idSum').text() != ""){
                            $('#piezasSum').html("");
                                
                            $.ajax({
                                type: "POST",
                                url: "getTicket3.php",
                                data: { 
                                    "idSum" :  $('#idSum').text().substring(7),
                                    "idProveedor": $('#proveedorSum').text()
                                },
                                success: function(data){
                                    $('#piezasSum').html(data);
                                }
                            });
                        }
                    });
                </script>
                </span>
            <table class="listP">    
                <tr class="blankspace">
                    <td ></td>
                    <td ></td>
                </tr>
                <tr>
                    <td class="TtoR">Total</td>
                    <td class="TtoC" id="totalSum"></td>
                </tr>
            </table>
        </div>
    </div>
    <!--/form-->
    <form method="POST" id="form1">
    <div class="cont">
        <div class="buttons">
            <!-- <button class="toR" type="submit" form="form1" value="Submit" name="Eliminar">Eliminar</button> -->
            <a href="#miModal"><button class="toR" form="form2" id="detail">Detalle</button></a>
            <a href="nueva_factura.php"><button class="toR" form="form2" >Nueva</button></a>
        </div>
        <div class="grid">
            <table id="TablaSum">
                <tr>
                    <th>Folio</th>
                    <th>Proveedor</th>
                    <th>Total</th>
                    <th>Fecha</th>
                </tr>
                <?php
                    $conexion;
                    MakeConnection();
                    Tabla("SuministraPieza");
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
    //header("Location: ventas.php");
}

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
    suministra($fila[1]);
}

function suministra($table){
    $user = "postgres";
    $password = "Alijonas_963";
    $dbname = "sta_rosa";
    $port = "5432";
    $host = "localhost";
    $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        
    try{
        $conexionpg = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
        $query = "select * from \"$table\"";
        $resultado = pg_query($conexionpg, $query) or die("Error en la Consulta SQL");
    
        while($fila = pg_fetch_array($resultado)){
            echo "<tr class='par'>";
            for($i = 0; $i < count($fila)/2; $i++){
                echo "<td>".$fila[$i]."</td>";
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

<script type='text/javascript'>
    var ultimaFila = null;
    var colorOriginal;

    $('#TablaSum tr').on('click',function(){
        //var dato = $(this).find('td:first').html();
        //$('#Resultado').val(dato);

        if(ultimaFila != null) {
            ultimaFila.css("background-color", colorOriginal);
        }
        

        colorOriginal = $(this).find('td').css('background-color');
        $(this).find('td').css("background-color","red");
        ultimaFila = $(this).find('td');

        $('#proveedorSum').text($(this).find('td:nth-child(2)').html());
        $('#idSum').text("Folio #" + $(this).find('td:first').html());
        $('#fechaSum').text("Fecha: " + $(this).find('td:nth-child(4)').html());
        $('#totalSum').text("$" + $(this).find('td:nth-child(3)').html());
        //console.log($('#empleadoVenta').text());
//console.log($('#idVenta').text());
    });
</script>