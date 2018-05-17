<?php

$conexionpg;
MakeConnection();
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

$id_venta = $_POST['idVenta'];
$id_empleado = $_POST['idEmpleado'];

echo "<table class='listP'>
        <tr>
            <th>Pieza</th>
            <th>Precio</th>
        </tr>";

//echo $id_venta;
//echo "emp".$id_empleado;
MakeTicket();
echo "</table>";

                    function MakeTicket(){
                        global $conexionpg;
                        $query = "select * from \"Fragmentos\" WHERE tabla='DetalleVenta'";
                        //echo $table;
                        $resultado = pg_query($conexionpg, $query) or die("Error en la Consulta SQL");
                        //echo $resultado;
                        $fila=pg_fetch_array($resultado);
                        $table = $fila[1];

                        $usuario = "root";
                        $fuente = "mysql:host=localhost;dbname=sta_rosa";
                        try{
                            $conexion = new PDO($fuente, $usuario);
                            global $id_venta;
                            if ($id_venta != ""){
                                $sql = "SELECT * FROM $table WHERE id_venta = ".$id_venta;
                                //echo $sql;
                                $resultado2 = $conexion->query($sql);
                                //var_dump($resultado2);
                                
                                while(($fila2 = $resultado2->fetch(PDO::FETCH_OBJ)) != FALSE){
                                    $i = 0;
                                    echo "<tr>";
                                    foreach($fila2 as $elemento){
                                        if ($i == 1){
                                            echo "<td>".$elemento."</td>";
                                            getPiece($elemento,"Pieza");
                                        }

                                        $i++;
                                    }
                                    echo "</tr>";
                                }

                                global $id_empleado;
                                getEmp("Empleado",$id_empleado);
                            }
                        }
                        catch(PDOException $ex){
                                echo 'Error en la conexión' . $ex->getMessage();
                        }
                    }

                    function getPiece($id_Pieza, $table){
                        global $conexionpg;
                        $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
                        
                        $resultado = pg_query($conexionpg, $query) or die("Error en la Consulta SQL");
                        
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
                            $query = "SELECT precio FROM \"$table\" WHERE id_pieza = '".$id_Pieza."'";
                            //echo "<td>".$query."s</td>";
                            $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");

                            if($fila = pg_fetch_array($resultado)){
                                echo "<td class='TtoC'>".$fila[0]."</td>";
                                return true;
                            }else{
                                return false;
                            }
                        }
                        catch(PDOException $ex){
                            echo 'Error en la conexión' . $ex->getMessage();
                            return;
                        }
                    }

                    function p2($table, $id_Pieza){
                        $usuario = "root";
                        $fuente = "mysql:host=localhost;dbname=sta_rosa";
                        try{
                            $conexion = new PDO($fuente, $usuario);
                            //echo 'Conexión establecida';
                            
                            $sql = "SELECT precio FROM $table WHERE id_pieza = '".$id_Pieza."'";
                            //echo $sql;
                            $resultado = $conexion->query($sql);
                            if(($fila = $resultado->fetch(PDO::FETCH_NUM)) != false){
                                echo "<td class='TtoC'>".$fila[0]."</td>";
                                return true;
                            }else {
                                return false;
                            }
                        }
                        catch(PDOException $ex){
                            echo 'Error en la conexión' . $ex->getMessage();
                            return;
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
                                echo "<script type='text/javascript'> $('#empleadoVenta').text('Atendió ".$fila[0]."'); </script>";
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
                            if(($fila = $resultado->fetch(PDO::FETCH_NUM)) != false){
                                echo "<script type='text/javascript'> $('#empleadoVenta').text('Atendió ".$fila[0]."'); </script>";
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