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
                Fulanito
                <br>123456789
            </div>
            <table class="listP">
                <tr>
                    <th>Pieza</th>
                    <th>Precio</th>
                </tr>
                <!-- <tr>
                    <td>ANIZIR250</td>
                    <td class="TtoC">$250</td>
                </tr> -->
                <span id="piezasApartado">
                <?php
                    $conexion;
                    MakeConnection();

                    function MakeTicket(){
                        global $conexionpg;
                        $query = "select * from \"Fragmentos\" WHERE tabla='DetalleApartado'";
                        //echo $table;
                        $resultado = pg_query($conexionpg, $query) or die("Error en la Consulta SQL");
                        //echo $resultado;
                        $fila=pg_fetch_array($resultado);
                        $table = $fila[1];

                        $user = "postgres";
                        $password = "Alijonas_963";
                        $dbname = "sta_rosa";
                        $port = "5432";
                        $host = "localhost";
                        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
                        try{
                            $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
                            $id_apartado = "<script> document.write($('idApartado').text() </script>";
                            $query = "SELECT * FROM \"$table\" WHERE id_apartado = ".$id_apartado;
                            $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");

                            while($fila = pg_fetch_array($resultado)) != false){
                                echo "<tr>";
                                echo "<td>".$fila[1]."</td>";
                                getPiece($fila[1],"Pieza");
                                echo "</tr>";
                            }

                            $id_emp = "<script> document.write($('empleadoApartado').text() </script>";
                            getEmp("Empleado",$id_emp);
                        }
                        catch(PDOException $ex){
                                echo 'Error en la conexión' . $ex->getMessage();
                        }
                    }

                    function getPiece($id_Pieza, $table){
                        global $conexionpg;
                        $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
                        //echo $table;
                        $resultado = pg_query($conexionpg, $query) or die("Error en la Consulta SQL");
                        //echo $resultado;
                        while($fila=pg_fetch_array($resultado)){
                            if ($fila[3] == 1){
                                if (p1($fila[1], $id_Pieza) == true) {
                                    break;
                                }
                            }else{
                                if (p2($fila[1], $id_Pieza) == true) {
                                    break;
                                }
                            }
                        }
                    }

                    function p1($table, $id_Pieza){
                        $user = "postgres";
                        $password = "Alijonas_963";
                        $dbname = "sta_rosa";
                        $port = "5432";
                        $host = "localhost";
                        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
                        try{
                            $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
                            $query = "SELECT precio FROM \"$table\" WHERE id_pieza = ".$id_Pieza;
                            $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");

                            if($fila = pg_fetch_array($resultado)){
                                echo "<td class='TtoC'>".$fila[0]."</td>";
                                return true;
                            }else{
                                return false;
                            }
                        }
                        catch(PDOException $ex){
                            return;
                            echo 'Error en la conexión' . $ex->getMessage();
                        }
                    }

                    function p2($table, $id_Pieza){
                        $usuario = "root";
                        $fuente = "mysql:host=localhost;dbname=sta_rosa";
                        try{
                            $conexion = new PDO($fuente, $usuario);
                            //echo 'Conexión establecida';
                            
                            $sql = "SELECT precio FROM $table WHERE id_pieza = ".$id_Pieza;
                            $resultado = $conexion->query($sql);
                            if($fila = $resultado->fetch(PDO::FETCH_NUM)) != false){
                                echo "<td class='TtoC'>".$fila[0]."</td>";
                                return true;
                            }else {
                                return false;
                            }
                        }
                        catch(PDOException $ex){
                            return;
                            echo 'Error en la conexión' . $ex->getMessage();
                        }
                    }

                    function getEmp($table, $id_emp){
                        global $conexionpg;
                        $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
                        $resultado = pg_query($conexionpg, $query) or die("Error en la Consulta SQL");

                        while($fila=pg_fetch_array($resultado)){
                            if ($fila[3] == 1){
                                if (e1($fila[1], $id_emp) == true) {
                                    break;
                                }
                            }else{
                                if (e2($fila[1], $id_emp) == true) {
                                    break;
                                }
                            }
                        }
                    }

                    function e1($table, $id_emp){
                        $user = "postgres";
                        $password = "Alijonas_963";
                        $dbname = "sta_rosa";
                        $port = "5432";
                        $host = "localhost";
                        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
                        try{
                            $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
                            $query = "SELECT nombre FROM \"$table\" WHERE id_empleado = ".$id_emp;
                            $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");

                            if($fila = pg_fetch_array($resultado)){
                                echo "<script> $('empleadoApartado').text('Atendió ".$fila[0]."'); </script>";
                                return true;
                            }else{
                                return false;
                            }
                        }
                        catch(PDOException $ex){
                            return;
                            echo 'Error en la conexión' . $ex->getMessage();
                        }
                    }

                    function e2($table, $id_emp){
                        $usuario = "root";
                        $fuente = "mysql:host=localhost;dbname=sta_rosa";
                        try{
                            $conexion = new PDO($fuente, $usuario);
                            //echo 'Conexión establecida';
                            
                            $sql = "SELECT nombre FROM $table WHERE id_empleado = ".$id_emp;
                            $resultado = $conexion->query($sql);
                            if($fila = $resultado->fetch(PDO::FETCH_NUM)) != false){
                                echo "<script> $('empleadoApartado').text('Atendió ".$fila[0]."'); </script>";
                                return true;
                            }else {
                                return false;
                            }
                        }
                        catch(PDOException $ex){
                            return;
                            echo 'Error en la conexión' . $ex->getMessage();
                        }
                    }

                ?>
                <script>
                    $('#idApartado').on('DOMSubtreeModified', function() {
                        if ($('#idApartado').text() != ""){
                            $('#piezasApartado').html("");
                            <?php echo MakeTicket(); ?>
                        }
                    });
                </script>
                </span>
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
            <input type="text" id="Resultado" />
            <div id="empleadoApartado">
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
function consulta($tabla){
        $user = "postgres";
        $password = "Alijonas_963";
        $dbname = "sta_rosa";
        $port = "5432";
        $host = "localhost";
        $cadenaConexion = "host=$host port=$port dbname=$dbname user=$user password=$password";
        try{
            $conexion = pg_connect($cadenaConexion) or die("Error en la Conexión: ".pg_last_error());
            $query = "select * from \"$tabla\"";
            $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
    
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
    global $conexion;
    try{
        
            $sql = "DELETE FROM \"$table\" WHERE id_apartado =" .$id ;
            //echo $sql;
            $resultado = pg_query($conexion, $sql) or die("Error en la Consulta SQL");
            header('Location: apartados.php');
            
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
        var dato = $(this).find('td:first').html();
        $('#Resultado').val(dato);

        if(ultimaFila != null) {
            ultimaFila.css("background-color", colorOriginal);
        }

        colorOriginal = $(this).find('td').css('background-color');
        $(this).find('td').css("background-color","red");
        ultimaFila = $(this).find('td');

        $('idApartado').text("Venta #" + $(this).find('td:first').html());
        $('empleadoApartado').text($(this).find('td:nth-child(2)').html());
        $('clienteApartado').text($(this).find('td:nth-child(3)').html() + " " + $(this).find('td:nth-child(4)').html());
        $('fechaApartado').text("Fecha: " + $(this).find('td:nth-child(7)').html());
        $('totalApartado').text("$" + $(this).find('td:nth-child(6)').html());
        $('abonoApartado').text("$" + $(this).find('td:nth-child(5)').html());
        var res = $(this).find('td:nth-child(6)').html() - $(this).find('td:nth-child(5)').html();
        $('restanteApartado').text("$" + res);
    });
</script>