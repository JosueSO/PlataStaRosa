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
            <li><a href="mayoristas.php">Mayoristas</a></li>
            <li><a href="empleados.php">Empleados</a></li>
            <li><a href="proveedores.php">Proveedores</a></li>
            <li><a class="active" href="facturas.php">Facturas</a></li>
          </ul>
    </div>
    <!--form id="form2"-->
    <!--/form-->
    <form method="POST" id="form1">
    <div class="cont">
        <div class="inputs">
            Cantidad <input type="number" name="cantidad" id="cantidad"/>
            <br>Clave <input type="text" name="clave" id="clave"/>
            <br><button class="toR" form="form2" id="agregar">Agregar</button>
        </div>
        <div class="inputs">
        Proveedor <select id="ComboProveedor" name="Proveedor">
                    </select>
        </div>
        <div class="grid">
            <table id="TablaCompra">
                <tr>
                    <th>Cantidad</th>
                    <th>Pieza</th>
                    <th>Costo</th> 
                </tr>
            </table>
        </div>
    </div>
    <div class="buttons">
    <button class="toR" form="" id="cobrar">Cobrar</button>
    </div>
    </form>
    <script>
        var total = 0;
        $(document).ready(function() {
            $.ajax({
                type: "POST",
                url: "getProveedores.php",
                data: {},
                success: function(data){
                    $('#ComboProveedor').html(data);
                }
            });
        });

        $('#agregar').on('click',function(){
            if ($('#clave').val() != ""){
                $.ajax({
                    type: "POST",
                    url: "getPieza.php",
                    data: { 
                        "idPieza" : $('#clave').val(),
                        "cantidad": $('#cantidad').val()
                    },
                    success: function(data){
                        $('#TablaCompra').append(data);
                        //alert(data);
                    }
                });
                                
            }
        });

        $("#cobrar").click(function () {
            var flag = false;
            var id_sum = 0;
            $.ajax({
                type: "POST",
                url: "makeSuministra1.php",
                data: {
                    "Proveedor": $('#ComboProveedor').val()
                },
                success: function(data){
                    id_sum = data;
                }
            }).done(function(){

                $("#TablaCompra tr").each(function (index) {
                    var campo1, campo2, campo3;
                    $(this).children("td").each(function (index2) {
                        switch (index2) {
                            case 0:
                                campo1 = $(this).text();
                                break;
                            case 1:
                                campo2 = $(this).text();
                                break;
                            case 2:
                                campo3 = $(this).text().substring(2);
                                total += Number(campo3);
                                break;
                        }
                    });

                    if (flag == true){
                        //alert(id_sum);
                        $.ajax({
                            type: "POST",
                            url: "makeSuministra.php",
                            data: { 
                                "idPieza"  : campo2,
                                "cantidad" : campo1,
                                "idSuministra": id_sum
                            },
                            success: function(data){
                                alert(data);
                            }
                        });
                    }
                    else
                        flag = true;
                });

                alert(total);
                $.ajax({
                    type: "POST",
                    url: "actualizaSum.php",
                    data: { 
                        "total"  : total,
                        "idSum": id_sum
                    },
                    success: function(data){
                        //alert(data);
                    }
                });
            });

            
        });
    </script>
</body>
</html>
