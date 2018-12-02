<?php 

include('menu.php');

?>
<div class="row">
    <div class="col-md-12">
        <h3>Buscador</h3>
    </div>  
</div>
<div class="row">
    <form id="resultados" action="#" method="post">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-8">
                    <input id="keyword" name="keyword" type="text" class="form-control" placeholder="Ingrese su búsqueda" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn verde"><i class="glyphicon glyphicon-search"></i> Buscar</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="row">
    <div class="col-md-12">
        <table style="margin-top:2%;" id="tabla_res" class="table table-bordered">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Fecha de inicio</th>
                    <th>Fecha de término</th>
                    <th>Precio</th>
                    <th>Imagen</th>
                    <th>Vendidos</th>
                    <th>Tags</th>
                </tr>
            </thead>
            <tbody>
                <tr id="vacio">
                    <td colspan="8" align="center">No hay resultados</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">

$('#resultados').on('submit', function(e) {
    e.preventDefault();
    var keyword = $('#keyword').val();
    $.ajax({
        type: "POST",
        url: "search",
        data: { keyword: keyword },
        success: function(data) {
            $('#vacio').html('');
            $('#tabla_res').find('tbody').html('');
            var resultados = "";
            if (data.length > 0) {
                for (var i = 0; i < data.length; i++) {
                    resultados += "<tr>";
                    resultados += "<td>"+ data[i].titulo + "</td>";
                    resultados += "<td>"+ data[i].descripcion + "</td>";
                    resultados += "<td>"+ data[i].fecha_inicio + "</td>";
                    resultados += "<td>"+ data[i].fecha_termino + "</td>";
                    resultados += "<td>"+ data[i].precio + "</td>";
                    if (data[i].imagen != undefined){
                        resultados += "<td><img src='"+ data[i].imagen + "' width='200' /></td>";
                    }
                    else{
                        resultados += "<td>Sin imagen</td>";
                    }
                    resultados += "<td>"+ data[i].vendidos + "</td>";
                    resultados += "<td>"+ data[i].tags + "</td>";
                    resultados += "</tr>";
                }
            }
            else {
                resultados += "<tr>";
                resultados += "<td colspan='8' align='center'>No hay resultados para su búsqueda</td>";
                resultados += "</tr>";
            }
            $('#tabla_res').find('tbody').append(resultados);
        }
    });
});

</script>

<?php 

include('footer.php');

?>