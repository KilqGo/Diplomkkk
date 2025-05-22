<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['adminflag'] != 1) {
    die('Доступ запрещен');
}

$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'web_magazin';

$link = mysqli_connect($host, $user, $password, $db_name);
if (!$link) die("Ошибка подключения: " . mysqli_connect_error());

$table = $_GET['table'] ?? '';
$id = (int)($_GET['id'] ?? 0);

if ($table !== 'assembly' || $id < 1) die('Неверные параметры');

$query = "SELECT * FROM assembly WHERE assembly_order_id = ?";
$stmt = $link->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$assembly = $stmt->get_result()->fetch_assoc();

if (!$assembly) die('Сборка не найдена');

$components = [
    'gpu' => ['table' => 'gpu', 'name_column' => 'gpu_name', 'fk_column' => 'gpu_id'],
    'mcase' => ['table' => 'mcase', 'name_column' => 'case_name', 'fk_column' => 'cse_id'],
    'motherboard' => ['table' => 'motherboard', 'name_column' => 'motherboard_name', 'fk_column' => 'mbd_id'],
    'powerunit' => ['table' => 'powerunit', 'name_column' => 'power_name', 'fk_column' => 'psu_id'],
    'processor' => ['table' => 'processor', 'name_column' => 'unit_name', 'fk_column' => 'cpu_id'],
    'ram' => ['table' => 'ram', 'name_column' => 'ram_name', 'fk_column' => 'ram_id'],
    'storage' => ['table' => 'storage', 'name_column' => 'storage_name', 'fk_column' => 'sdu_id']
];

foreach ($components as $key => $component) {
    $query = "SELECT {$component['name_column']} 
              FROM {$component['table']} 
              WHERE {$component['fk_column']} = ?";
    
    $stmt = $link->prepare($query);
    $stmt->bind_param('i', $assembly[$component['fk_column']]);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $assembly[$key] = $result[$component['name_column']] ?? 'Не указано';
}

$html = '<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Сборка #'.$assembly['assembly_order_id'].'</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: #2c3e50; }
        ul { list-style-type: none; padding: 0; }
        li { margin: 8px 0; }
    </style>
</head>
<body>
    <h1>Детали сборки #'.$assembly['assembly_order_id'].'</h1>
    
    <div>
        <p><strong>Цена:</strong> '.number_format($assembly['assembly_price'], 0, '', ' ').' руб.</p>
        <p><strong>Дата составления:</strong> '.$assembly['date_of_admission'].'</p>
        <p><strong>Адрес доставки:</strong> '.$assembly['delivery_address'].'</p>
    </div>

    <h2>Компоненты:</h2>
    <ul>';

foreach ($components as $key => $component) {
    $html .= '<li><strong>'.ucfirst($key).':</strong> '.$assembly[$key].'</li>';
}

$html .= '</ul>
</body>
</html>';

header("Content-Type: application/msword");
header("Content-Disposition: attachment; filename=assembly_".$id.".doc");
header("Content-Transfer-Encoding: binary");
header("Expires: 0");
echo $html;

mysqli_close($link);
exit;
?>