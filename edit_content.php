<?php
session_start();
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'web_magazin';

$link = mysqli_connect($host, $user, $password, $db_name);

if (!$link) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_id']) || $_SESSION['adminflag'] != 1) {
    header("Location: login.php");
    exit;
}

$selected_table = $_GET['table'] ?? 'assembly';
$search_query = $_GET['search'] ?? '';

// Массивы данных для таблиц
$table_headers = [
    'assembly' => ['ID', 'Цена', 'Дата составления', 'Дата доставки', 'Адрес доставки', 'ID клиента', 'ID мастера', 'GPU ID', 'Case ID', 'Motherboard ID', 'Power Unit ID', 'Processor ID', 'RAM ID', 'Storage ID', 'Действия'],
    'master' => ['ID', 'Имя', 'Телефон', 'Адрес', 'Действия'],
    'customer' => ['ID', 'Имя', 'Телефон', 'Адрес', 'Действия'],
    'gpu' => ['ID', 'Название', 'Объем памяти', 'Серия', 'Производитель', 'Цена', 'Действия'],
    'mcase' => ['ID', 'Название', 'Форм-фактор', 'Размер', 'Производитель', 'Цена', 'Действия'],
    'motherboard' => ['ID', 'Название', 'Форм-фактор', 'Чипсет', 'Сокет', 'Производитель', 'Цена', 'Действия'],
    'powerunit' => ['ID', 'Название', 'Мощность', 'Производитель', 'Цена', 'Действия'],
    'processor' => ['ID', 'Название', 'Сокет', 'Базовая частота', 'Количество ядер', 'Производитель', 'Цена', 'Действия'],
    'ram' => ['ID', 'Название', 'Объем памяти', 'Тип', 'Частота', 'Производитель', 'Цена', 'Действия'],
    'storage' => ['ID', 'Название', 'Объем', 'Скорость чтения', 'Тип', 'Производитель', 'Цена', 'Действия']
];

$table_columns = [
    'assembly' => ['assembly_order_id', 'assembly_price', 'date_of_admission', 'date_of_delivery', 'delivery_address', 'ctr_id', 'mtr_id', 'gpu_id', 'cse_id', 'mbd_id', 'psu_id', 'cpu_id', 'ram_id', 'sdu_id'],
    'master' => ['mtr_id', 'full_name', 'phone_number', 'legal_address'],
    'customer' => ['ctr_id', 'full_name', 'phone_number', 'legal_address'],
    'gpu' => ['gpu_id', 'gpu_name', 'gmemory_size', 'gpu_series', 'gpu_manufacturer', 'price'],
    'mcase' => ['cse_id', 'case_name', 'form_factor', 'case_size', 'case_manufacturer', 'price'],
    'motherboard' => ['mbd_id', 'motherboard_name', 'form_factor', 'chipset', 'socket', 'board_manufacturer', 'price'],
    'powerunit' => ['psu_id', 'power_name', 'capability', 'power_manufacturer', 'price'],
    'processor' => ['cpu_id', 'unit_name', 'socket', 'base_frequency', 'number_of_cores', 'cpu_manufacturer', 'price'],
    'ram' => ['ram_id', 'ram_name', 'memory_size', 'type', 'base_frequency', 'ram_manufacturer', 'price'],
    'storage' => ['sdu_id', 'storage_name', 'storage_capacity', 'reading_speed', 'sdu_type', 'sdu_manufacturer', 'price']
];

$search_columns = [
    'assembly' => 'assembly_order_id',
    'master' => 'full_name',
    'customer' => 'full_name',
    'gpu' => 'gpu_name',
    'mcase' => 'case_name',
    'motherboard' => 'motherboard_name',
    'powerunit' => 'power_name',
    'processor' => 'unit_name',
    'ram' => 'ram_name',
    'storage' => 'storage_name'
];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель PC Club</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #1e1e1e;
            color: white;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #4CAF50;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 25px;
            margin: 15px 0;
        }

        nav ul li a {
            color: #4CAF50;
            text-decoration: none;
            font-size: 1.1em;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #45a049;
            text-decoration: underline;
        }

        .main-container {
            width: 85%;
            margin: 25px auto;
            background-color: #2c2c2c;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }

        .controls {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            flex-wrap: wrap;
            align-items: center;
        }

        select {
            padding: 10px 15px;
            border-radius: 6px;
            border: 2px solid #4CAF50;
            background-color: #1e1e1e;
            color: white;
            font-size: 16px;
            min-width: 200px;
        }

        .search-group {
            display: flex;
            gap: 10px;
            flex-grow: 1;
        }

        input[type="text"] {
            padding: 10px;
            border-radius: 6px;
            border: 2px solid #4CAF50;
            background-color: #1e1e1e;
            color: white;
            font-size: 16px;
            flex-grow: 1;
        }

        button, .button {
            padding: 10px 25px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 16px;
        }

        button:hover, .button:hover {
            background-color: #45a049;
            transform: translateY(-1px);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #1e1e1e;
            border-radius: 8px;
            overflow: hidden;
        }

        .data-table th, .data-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #3d3d3d;
        }

        .data-table th {
            background-color: #4CAF50;
            color: white;
        }

        .data-table tr:hover {
            background-color: #2c2c2c;
        }

        .actions-cell {
            white-space: nowrap;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #1e1e1e;
            color: white;
            margin-top: auto;
            border-top: 2px solid #4CAF50;
        }

        @media (max-width: 768px) {
            .main-container {
                width: 95%;
                padding: 15px;
            }
            
            .controls {
                flex-direction: column;
            }
            
            select, input[type="text"] {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>🔧 Админ-панель PC Club</h1>
        <nav>
            <ul>
                <li><a href="stock_management.php">📦 Склад</a></li>
                <li><a href="index.php">🏠 Главная</a></li>
                <li><a href="order_form.php">🛒 Оформить заказ</a></li>
                <li><a href="logout.php">🚪 Выйти</a></li>
            </ul>
        </nav>
    </header>

    <div class="main-container">
        <form method="get">
            <div class="controls">
                <select name="table" onchange="this.form.submit()">
                    <option value="assembly" <?= $selected_table == 'assembly' ? 'selected' : '' ?>>🎁 Сборки</option>
                    <option value="master" <?= $selected_table == 'master' ? 'selected' : '' ?>>👨💼 Мастера</option>
                    <option value="customer" <?= $selected_table == 'customer' ? 'selected' : '' ?>>👥 Клиенты</option>
                    <option value="gpu" <?= $selected_table == 'gpu' ? 'selected' : '' ?>>🎮 Видеокарты</option>
                    <option value="mcase" <?= $selected_table == 'mcase' ? 'selected' : '' ?>>🖥️ Корпуса</option>
                    <option value="motherboard" <?= $selected_table == 'motherboard' ? 'selected' : '' ?>>🔌 Материнки</option>
                    <option value="powerunit" <?= $selected_table == 'powerunit' ? 'selected' : '' ?>>🔋 Блоки питания</option>
                    <option value="processor" <?= $selected_table == 'processor' ? 'selected' : '' ?>>⚡ Процессоры</option>
                    <option value="ram" <?= $selected_table == 'ram' ? 'selected' : '' ?>>💾 ОЗУ</option>
                    <option value="storage" <?= $selected_table == 'storage' ? 'selected' : '' ?>>💽 Накопители</option>
                </select>

                <div class="search-group">
                    <input type="text" name="search" placeholder="🔍 Поиск..." value="<?= htmlspecialchars($search_query) ?>">
                    <button type="submit">Найти</button>
                </div>
                
                <a href="add_data.php?table=<?= $selected_table ?>" class="button">➕ Добавить запись</a>
            </div>
        </form>

        <table class="data-table">
            <thead>
                <tr>
                    <?php foreach ($table_headers[$selected_table] as $header): ?>
                        <th><?= htmlspecialchars($header) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM $selected_table";
                $params = [];
                
                if (!empty($search_query)) {
                    $search_column = $search_columns[$selected_table];
                    $sql .= " WHERE $search_column LIKE ?";
                    $params[] = "%$search_query%";
                }
                
                $stmt = $link->prepare($sql);
                
                if ($stmt) {
                    if (!empty($params)) {
                        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
                    }
                    
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()):
                ?>
                        <tr>
                            <?php foreach ($table_columns[$selected_table] as $column): ?>
                                <td><?= htmlspecialchars($row[$column] ?? 'N/A') ?></td>
                            <?php endforeach; ?>
                            <td class="actions-cell">
                                <button onclick="editData('<?= $selected_table ?>', <?= $row[$table_columns[$selected_table][0]] ?>)">✏️</button>
                                <button onclick="deleteData('<?= $selected_table ?>', <?= $row[$table_columns[$selected_table][0]] ?>)">🗑️</button>
                            </td>
                        </tr>
                <?php
                    endwhile;
                } else {
                    echo '<tr><td colspan="100%">Ошибка выполнения запроса</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <footer>
        © <?= date('Y') ?> PC Club | Все права защищены
    </footer>

    <script>
        function editData(table, id) {
            window.location.href = `edit_form.php?table=${table}&id=${id}`;
        }

        function deleteData(table, id) {
            if (confirm('Вы уверены, что хотите удалить запись?')) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_data.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        alert(xhr.responseText);
                        location.reload();
                    } else {
                        alert('Ошибка при удалении');
                    }
                };
                xhr.send(`table=${table}&id=${id}`);
            }
        }
    </script>
</body>
</html>
<?php mysqli_close($link); ?>