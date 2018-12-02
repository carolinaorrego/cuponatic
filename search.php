<?php 

include('conexion.php');
header('Content-Type: application/json');

//Verificamos si la palabra ha sido buscada con anterioridad
$keyword = "{$_POST['keyword']}";
$stmt = $mysqli->prepare("SELECT * FROM busqueda WHERE palabra = ?");
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result = $stmt->get_result();
$num_rows = mysqli_num_rows($result);

while($row = $result->fetch_assoc()) {
    $id_busqueda = $row['id_busqueda'];
}

//Si la palabra nunca ha sido buscada se agrega
if ($num_rows == 0) {
    $busqueda = "{$_POST['keyword']}";
    $stmt = $mysqli->prepare("INSERT INTO busqueda (palabra) VALUES (?)");
    $stmt->bind_param("s", $busqueda);
    $stmt->execute();
    $id_busqueda = mysqli_insert_id();
}

//Buscamos los productos segun el keyword ingresado
$keyword = "%{$_POST['keyword']}%";
$stmt = $mysqli->prepare("SELECT * FROM productos WHERE titulo LIKE ?");
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result = $stmt->get_result();
$arr  = [];

while($row = $result->fetch_assoc()) {
    $arr[] = $row;
}

foreach ($arr as $row){
    $id_producto = $row['id_producto'];
    //Se busca estadistica de esa busqueda y producto
    $stmt = $mysqli->prepare("SELECT * FROM detalle_busqueda WHERE id_producto = ? AND id_busqueda = ?");
    $stmt->bind_param("ii", $id_producto, $id_busqueda);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_rows = mysqli_num_rows($result);

    //Si la palabra nunca ha sido buscada se agrega
    if ($num_rows == 0) {
        $stmt = $mysqli->prepare("INSERT INTO detalle_busqueda(id_producto,id_busqueda,frecuencia) VALUES (?, ?, 1)");
        $stmt->bind_param("ii", $id_producto, $id_busqueda);
        $stmt->execute();
    }
    else{
        $stmt = $mysqli->prepare("UPDATE detalle_busqueda SET frecuencia = frecuencia + 1 WHERE id_producto = ? AND id_busqueda = ?");
        $stmt->bind_param("ii", $id_producto, $id_busqueda);
        $stmt->execute();
    }
    $stmt = $mysqli->prepare("UPDATE productos SET frecuencia = frecuencia + 1 WHERE id_producto = ?");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
}

echo json_encode($arr);