<?php
$host = 'localhost'; 
$user = 'root';      
$password = '';      
$db_name = 'web_magazin'; 

$link = mysqli_connect($host, $user, $password, $db_name);

if (!$link) {
    die(json_encode(["error" => "Ошибка подключения: " . mysqli_connect_error()]));
}

// Получаем таблицу для выборки данных
$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? 0;

// Выбираем таблицу
if ($table === 'assembly') {
    $stmt = $link->prepare("SELECT * FROM assembly WHERE assembly_order_id = ?");
} elseif ($table === 'master') {
    $stmt = $link->prepare("SELECT * FROM master WHERE mtr_id = ?");
} elseif ($table === 'customer') {
    $stmt = $link->prepare("SELECT * FROM customer WHERE ctr_id = ?");
} elseif ($table === 'gpu') {
    $stmt = $link->prepare("SELECT * FROM gpu WHERE gpu_id = ?");
} elseif ($table === 'mcase') {
    $stmt = $link->prepare("SELECT * FROM mcase WHERE cse_id = ?");
} elseif ($table === 'motherboard') {
    $stmt = $link->prepare("SELECT * FROM motherboard WHERE mbd_id = ?");
} elseif ($table === 'powerunit') {
    $stmt = $link->prepare("SELECT * FROM powerunit WHERE psu_id = ?");
} elseif ($table === 'processor') {
    $stmt = $link->prepare("SELECT * FROM processor WHERE cpu_id = ?");
} elseif ($table === 'ram') {
    $stmt = $link->prepare("SELECT * FROM ram WHERE ram_id = ?");
} elseif ($table === 'storage') {
    $stmt = $link->prepare("SELECT * FROM storage WHERE sdu_id = ?");
} else {
    die(json_encode(["error" => "Неизвестная таблица."]));
}

// Привязываем параметры и выполняем запрос
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode($data);

$stmt->close();
mysqli_close($link);
?>
