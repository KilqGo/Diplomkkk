<?php
$host = 'localhost'; 
$user = 'root';      
$password = '';      
$db_name = 'web_magazin'; 

$link = mysqli_connect($host, $user, $password, $db_name);

if (!$link) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

$table = $_POST['table'] ?? '';
$id = $_POST['id'] ?? 0;

// Функция для получения имени столбца ID
function getIdColumn($table) {
    switch ($table) {
        case 'assembly': return 'assembly_order_id';
        case 'master': return 'mtr_id';
        case 'customer': return 'ctr_id';
        case 'gpu': return 'gpu_id';
        case 'mcase': return 'cse_id';
        case 'motherboard': return 'mbd_id';
        case 'powerunit': return 'psu_id';
        case 'processor': return 'cpu_id';
        case 'ram': return 'ram_id';
        case 'storage': return 'sdu_id';
        default: return 'id'; 
    }
}

// Удаляем данные из базы данных
$idColumn = getIdColumn($table);
$sql = "DELETE FROM $table WHERE $idColumn = ?";
$stmt = $link->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Запись успешно удалена.";
} else {
    echo "Ошибка удаления записи: " . $link->error;
}

$stmt->close();
mysqli_close($link);
?>
