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
            <li><a class="active" href="">Apartados</a></li>
            <li><a href="mayoristas.php">Mayoristas</a></li>
            <li><a href="empleados.php">Empleados</a></li>
            <li><a href="proveedores.php">Proveedores</a></li>
            <li><a href="facturas.php">Factura</a></li>
          </ul>
    </div>
    <!--form id="form2"-->
    <div id="miModal" class="modal">
        <div class="ticket">
            <div>
                <div id="idApartado">Apartado #1</div>
                <div class="closeB"><a href="#">X</a></div>
            </div>
            <div class="TtoR" id="fechaApartado">
                2018/04/01
            </div>
            <div id="clienteApartado">
            </div>
                <!-- <tr>
                    <th>Pieza</th>
                    <th>Precio</th>
                </tr> -->
                <!-- <tr>
                    <td>ANIZIR250</td>
                    <td class="TtoC">$250</td>
                </tr> -->
                <span id="piezasApartado">
                
                <script type='text/javascript'>
                    $('#idApartado').on('DOMSubtreeModified', function() {
                        if ($('#idApartado').text() != ""){
                            //console.log("Hola");
                            $('#piezasApartado').html("");
                            //console.log($('#empleadoVenta').text());
                            $.ajax({
                                type: "POST",
                                url: "getTicket2.php",
                                data: { 
                                    "idApartado" :  $('#idApartado').text().substring(10),
                                    "idEmpleado": $('#empleadoApartado').text()
                                },
                                success: function(data){
                                    $('#piezasApartado').html(data);
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
                    <td class="TtoC" id="totalApartado"></td>
                </tr>
                <tr>
                    <td class="TtoR">Abono</td>
                    <td class="TtoC" id="abonoApartado"></td>
                </tr>
                <tr>
                    <td class="TtoR">Restante</td>
                    <td class="TtoC" id="restanteApartado"></td>
                </tr>
            </table>
            <div id="empleadoApartado">
            </div>
        </div>
    </div>
    <!--/form-->
    <form method="POST" id="form1">
    <div class="cont">
        <div class="buttons">
            <button class="toR" type="submit" form="form1" value="Submit" name="Eliminar">Eliminar</button>
            <button class="toR" form="form2" id="Liquidar">Liquidar</button>
            <a href="#miModal"><button class="toR" form="form2" id="detail">Detalle</button></a>
        </div>
        <div class="grid">
            <table id="TablaApartados">
                <tr>
                    <th>Folio</th>
                    <th>Empleado</th>
                    <th>Cliente</th> 
                    <th>Teléfono</th>
                    <th>Abonado</th>
                    <th>Total</th>
                    <th>Fecha</th>
                </tr>
                <?php
                    $conexion;
                    MakeConnection();
                    Apartados("Apartado");
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
    eliminar('Apartado',$_POST['RES']);
    header("Location: apartados.php");
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
        //echo "<h3>Conexion Exitosa PHP - PostgreSQL</h3><hr><br>";

        $query = "select * from \"Atributos\"";
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        //var_dump($resultado);
        $fila=pg_fetch_array($resultado);
        //echo $fila[1];
    }

function Apartados($table){
        //MakeConnection();
        global $conexion;
        $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        //var_dump($resultado);
        //$i = 0;
        while($fila=pg_fetch_array($resultado)){
            consulta($fila[1]);
        }
  }   
  $conexionpg;
function consulta($tabla){
    global $conexionpg;
        $user = "postgres";
        $password = "Alijonas_963";
        $dbname = "sta_rosa";
        $port = "5432";
        $host = "localhost";
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        try{
            $conexionpg = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
            $query = "select * from \"$tabla\"";
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
    global $conexion;
    $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
    echo $table;
    $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
    echo $resultado;
    $fila=pg_fetch_array($resultado);
    eliminaPG($fila[1],$id);
}

function eliminaPG($table, $id){
    global $conexionpg;
    try{
        
            $sql = "DELETE FROM \"$table\" WHERE id_apartado =" .$id ;
            //echo $sql;
            $resultado = pg_query($conexionpg, $sql) or die("Error en la Consulta SQL");
            //header('Location: apartados.php');
            
        }
    catch(PDOException $ex){
            echo 'Error en la conexión' . $ex->getMessage();
    }
}
?>

<script>
    var ultimaFila = null;
    var colorOriginal;

    $('#TablaApartados tr').on('click',function(){

        if(ultimaFila != null) {
            ultimaFila.css("background-color", colorOriginal);
        }

        colorOriginal = $(this).find('td').css('background-color');
        $(this).find('td').css("background-color","red");
        ultimaFila = $(this).find('td');
        $('#Resultado').val($(this).find('td:first').html()); 
        $('#empleadoApartado').text($(this).find('td:nth-child(2)').html());
        $('#idApartado').text("Apartado #" + $(this).find('td:first').html());
        $('#clienteApartado').text($(this).find('td:nth-child(3)').html() + " " + $(this).find('td:nth-child(4)').html());
        $('#fechaApartado').text("Fecha: " + $(this).find('td:nth-child(7)').html());
        $('#totalApartado').text("$" + $(this).find('td:nth-child(6)').html());
        $('#abonoApartado').text("$" + $(this).find('td:nth-child(5)').html());
        var res = $(this).find('td:nth-child(6)').html() - $(this).find('td:nth-child(5)').html();
        $('#restanteApartado').text("$" + res);
    });

    $('#Liquidar').on('click',function(){
        if ($('#idApartado').text() != ""){
            $.ajax({
                type: "POST",
                url: "liquidar_apartado.php",
                data: { 
                    "idApartado" :  $('#idApartado').text().substring(10)
                },
                success: function(data){
                    alert(data);
                    window.location.href = "http://localhost/PlataStaRosa/ventas.php#";
                }
            });
                            
        }
    });
</script>