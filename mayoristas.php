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
            <li><a href="factura.php">Factura</a></li>
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

function Mayoristas(){
    $usuario = "root";
    $fuente = "mysql:host=localhost;dbname=sta_rosa";
    try{
        $conexion = new PDO($fuente, $usuario);
        //echo 'Conexión establecida';
        
        $sql = "SELECT * FROM mayoristas2";
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