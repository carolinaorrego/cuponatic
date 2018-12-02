<?php 

include('menu.php');
include('conexion.php');

//Buscamos los productos mas buscados
$sql = "SELECT id_producto, titulo, frecuencia FROM productos ORDER BY frecuencia DESC";
$resultado = $mysqli->query($sql);
$num_prod = 0;
$productos = [];

while ($row = $resultado->fetch_assoc()){
    if($num_prod < 20 && $row['frecuencia'] > 0){
        $productos[] = $row; 
        $num_prod = $num_prod + 1;   
    }
}

$palabras = [];
$resultados = [];

//Buscamos las palabras para los productos mas buscados
foreach ($productos as $row){
    $id_producto = $row['id_producto'];
    $stmt = $mysqli->prepare("SELECT * FROM detalle_busqueda LEFT JOIN busqueda ON detalle_busqueda.id_busqueda = busqueda.id_busqueda WHERE id_producto = ? ORDER BY frecuencia DESC");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_prod = 0;

    while($row = $result->fetch_assoc()) {
        if($num_prod < 5){
            $palabras[] = $row;
        $num_prod = $num_prod + 1;   
        }
    }
}

?>
<h3>Estadísticas</h3>
<p>Lista de productos más buscados</p>            
<table id="estadisticas" class="table table-bordered">
    <thead>
    		<tr>
        <th>Título</th>
            <th>Palabras buscadas</th>
    		</tr>
    </thead>
    <tbody>
        <?php 
        foreach ($productos as $prod){
            echo "<tr><td>".$prod['titulo']."</td><td>";
            foreach ($palabras as $pal){
                if($prod['id_producto'] == $pal['id_producto']){
                  echo $pal['palabra'].", ";
                }
            }
            echo "</td><tr>";
        }
        ?>
    </tbody>
</table>