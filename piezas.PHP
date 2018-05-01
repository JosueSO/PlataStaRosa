<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Plata Sta. Rosa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
    <?php
    piezas();
    ?>
    <div id="logo">
        <img src="images/logoPlata.png"/>
    </div>
    <div class="navi">
        <ul>
            <li><a class="active" href="">Piezas</a></li>
            <li><a href="ventas.html">Ventas</a></li>
            <li><a href="apartados.html">Apartados</a></li>
            <li><a href="mayoristas.html">Mayoristas</a></li>
            <li><a href="empleados.html">Empleados</a></li>
            <li><a href="proveedores.html">Proveedores</a></li>
          </ul>
    </div>
    <div class="cont">
        <div class="buttons">
            <a href="familias.html"><button class="toL">Familias</button></a>
            <button class="toL">Categorias</button>
            <button class="toR">Eliminar</button>
            <button class="toR">Editar</button>
            <a href="nueva_pieza.html"><button class="toR">Nuevo</button></a>
        </div>
        <div class="grid">
            <table>
                <tr>
                    <th>Código</th>
                    <th>Familia</th> 
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Existencia</th>  
                </tr>
                <tr class="par">
                    <td>ANIZIR225</td>
                    <td>Anillo</td> 
                    <td>Zirconia</td>
                    <td class="TtoR">$225</td>
                    <td class="TtoC">20</td>
                </tr>
                <tr class="impar">
                    <td>ANIZIR225</td>
                    <td>Anillo</td> 
                    <td>Zirconia</td>
                    <td class="TtoR">$225</td>
                    <td class="TtoC">20</td>
                </tr>
                <tr class="par">
                    <td>ANIZIR225</td>
                    <td>Anillo</td> 
                    <td>Zirconia</td>
                    <td class="TtoR">$225</td>
                    <td class="TtoC">20</td>
                </tr>
                    <tr class="impar">
                    <td>ANIZIR225</td>
                    <td>Anillo</td> 
                    <td>Zirconia</td>
                    <td class="TtoR">$225</td>
                    <td class="TtoC">20</td>
                </tr>
                <tr class="par">
                    <td>ANIZIR225</td>
                    <td>Anillo</td> 
                    <td>Zirconia</td>
                    <td class="TtoR">$225</td>
                    <td class="TtoC">20</td>
                </tr>
                <tr class="impar">
                    <td>ANIZIR225</td>
                    <td>Anillo</td> 
                    <td>Zirconia</td>
                    <td class="TtoR">$225</td>
                    <td class="TtoC">20</td>
                </tr>
                <tr class="par">
                    <td>ANIZIR225</td>
                    <td>Anillo</td> 
                    <td>Zirconia</td>
                    <td class="TtoR">$225</td>
                    <td class="TtoC">20</td>
                </tr>
                <tr class="impar">
                    <td>ANIZIR225</td>
                    <td>Anillo</td> 
                    <td>Zirconia</td>
                    <td class="TtoR">$225</td>
                    <td class="TtoC">20</td>
                </tr>
                    <tr class="par">
                    <td>ANIZIR225</td>
                    <td>Anillo</td> 
                    <td>Zirconia</td>
                    <td class="TtoR">$225</td>
                    <td class="TtoC">20</td>
                </tr>
                <tr class="impar">
                    <td>ANIZIR225</td>
                    <td>Anillo</td> 
                    <td>Zirconia</td>
                    <td class="TtoR">$225</td>
                    <td class="TtoC">20</td>
                </tr>
                <tr class="par">
                    <td>ANIZIR225</td>
                    <td>Anillo</td> 
                    <td>Zirconia</td>
                    <td class="TtoR">$225</td>
                    <td class="TtoC">20</td>
                </tr>
                <tr class="impar">
                    <td>ANIZIR225</td>
                    <td>Anillo</td> 
                    <td>Zirconia</td>
                    <td class="TtoR">$225</td>
                    <td class="TtoC">20</td>
                </tr>
                <tr class="par">
                    <td>ANIZIR225</td>
                    <td>Anillo</td> 
                    <td>Zirconia</td>
                    <td class="TtoR">$225</td>
                    <td class="TtoC">20</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>

<?php
    function piezas(){
        $query = "select * from Fragmentos WHERE tabla='Pieza'";
        $resultado = pg_query($conexion, $query) or die("Error en la Consulta SQL");
        var_dump($resultado);
        $i = 0;
        while($resultado){
            $fila=pg_fetch_array($resultado);
            echo $fila[$i];
            $i++;
         }
        
}
    }
?>