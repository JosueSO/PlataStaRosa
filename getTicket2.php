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

$id_apartado = $_POST['idApartado'];
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
                            global $id_apartado;
                            $query = "SELECT * FROM \"$table\" WHERE id_apartado = ".$id_apartado;
                            $resultado2 = pg_query($conexion, $query) or die("Error en la Consulta SQL");

                            while(($fila2 = pg_fetch_array($resultado2)) != false){
                                echo "<tr>";
                                echo "<td>".$fila2[1]."</td>";
                                getPiece($fila2[1],"Pieza");
                                echo "</tr>";
                            }

                            global $id_empleado;
                            getEmp("Empleado",$id_empleado);
                        }
                        catch(PDOException $ex){
                                echo 'Error en la conexión' . $ex->getMessage();
                        }
                    }

                    function getPiece($id_Pieza, $table){
                        global $conexionpg;
                        $query = "select * from \"Fragmentos\" WHERE tabla='$table'";
                        
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
                            $query = "SELECT precio FROM \"$table\" WHERE id_pieza = '".$id_Pieza."'";
                            
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
                            
                            $sql = "SELECT precio FROM $table WHERE id_pieza = '".$id_Pieza."'";
                            
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
                                echo "<script> $('#empleadoApartado').text('Atendió ".$fila[0]."'); </script>";
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
                                echo "<script> $('#empleadoApartado').text('Atendió ".$fila[0]."'); </script>";
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