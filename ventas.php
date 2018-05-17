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
                <div id="idVenta"></div>
                <div class="closeB"><a href="#">X</a></div>
            </div>
            <div class="TtoR" id="fechaVenta"></div>
            <div class="TtoR" id="clienteVenta">
                Josue
            </div>
            <!-- <table class="listP"> -->
                <!-- <tr>
                    <td>AREPAS150</td>
                    <td class="TtoC">$150</td>
                </tr> -->
                <span id="piezasVenta">
                <script type='text/javascript'>
                    
                        $('#idVenta').on('DOMSubtreeModified', function() {
                            if ($('#idVenta').text() != ""){
                                //console.log("Hola");
                                $('#piezasVenta').html("");
                                //console.log($('#empleadoVenta').text());
                                $.ajax({
                                    type: "POST",
                                    url: "getTicket.php",
                                    data: { 
                                        "idVenta" :  $('#idVenta').text().substring(7),
                                        "idEmpleado": $('#empleadoVenta').text()
                                    },
                                    success: function(data){
                                        $('#piezasVenta').html(data);
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
                    <td class="TtoR">Subtotal</td>
                    <td class="TtoC" id="subtotalVenta"></td>
                </tr>
                <tr>
                    <td class="TtoR">Descuento</td>
                    <td class="TtoC" id="descuentoVenta"></td>
                </tr>
                <tr>
                    <td class="TtoR">Total</td>
                    <td class="TtoC" id="totalVenta"></td>
                </tr>
            </table>
            
            <div id="empleadoVenta">
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
            <input type="text" id="R1" name="folio"/>
            <input type="text" id="R2" name="empleado"/>
            <input type="text" id="R3" name="mayorista"/>
            <input type="text" id="R4" name="fecha"/>
            <input type="text" id="R5" name="subtotal"/>
            <input type="text" id="R6" name="descuento"/>
            <input type="text" id="R7" name="total"/>
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

<script type='text/javascript'>
    var ultimaFila = null;
    var colorOriginal;

    $('#TablaVentas tr').on('click',function(){
        //var dato = $(this).find('td:first').html();
        //$('#Resultado').val(dato);

        if(ultimaFila != null) {
            ultimaFila.css("background-color", colorOriginal);
        }

        colorOriginal = $(this).find('td').css('background-color');
        $(this).find('td').css("background-color","red");
        ultimaFila = $(this).find('td');
        $('#Resultado').val($(this).find('td:first').html()); 
        $('#R1').val($(this).find('td:first').html()); 
        $('#R2').val($(this).find('td:nth-child(2)').html());
        $('#R3').val($(this).find('td:nth-child(3)').html());
        $('#R4').val($(this).find('td:nth-child(4)').html());
        $('#R5').val($(this).find('td:nth-child(5)').html());
        $('#R6').val($(this).find('td:nth-child(6)').html());
        $('#R7').val($(this).find('td:nth-child(7)').html());

        $('#empleadoVenta').text($(this).find('td:nth-child(2)').html());
        $('#idVenta').text("Venta #" + $(this).find('td:first').html());
        
        $('#fechaVenta').text("Fecha: " + $(this).find('td:nth-child(4)').html());
        $('#subtotalVenta').text("$" + $(this).find('td:nth-child(5)').html());
        $('#descuentoVenta').text($(this).find('td:nth-child(6)').html() + "%");
        $('#totalVenta').text("$" + $(this).find('td:nth-child(7)').html());
        //console.log($('#empleadoVenta').text());
//console.log($('#idVenta').text());
    });
</script>