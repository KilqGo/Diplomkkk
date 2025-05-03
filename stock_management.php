<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['adminflag'] != 1) {
    header("Location: login.php");
    exit;
}

$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'web_magazin';
$link = mysqli_connect($host, $user, $password, $db_name);

if (!$link) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_stock'])) {
    $table = $_POST['table'];
    $id = $_POST['id'];
    $new_stock = (int)$_POST['stock'];
    
    $stmt = $link->prepare("UPDATE $table SET stock = ? WHERE {$table}_id = ?");
    $stmt->bind_param("ii", $new_stock, $id);
    
    if ($stmt->execute()) {
        $_SESSION['stock_message'] = "✅ Запас успешно обновлен!";
    } else {
        $_SESSION['stock_error'] = "❌ Ошибка: " . $stmt->error;
    }
    $stmt->close();
    header("Location: ".$_SERVER['PHP_SELF']."?table=".$_GET['table']);
    exit;
}

$selected_table = $_GET['table'] ?? 'gpu';
$search_query = $_GET['search'] ?? '';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Склад PC Club</title>
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

        .stock-container {
            width: 85%;
            margin: 25px auto;
            background-color: #2c2c2c;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }

        .filters {
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

        button {
            padding: 10px 25px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
            transform: translateY(-1px);
        }

        .stock-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #1e1e1e;
            border-radius: 8px;
            overflow: hidden;
        }

        .stock-table th, .stock-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #3d3d3d;
        }

        .stock-table th {
            background-color: #4CAF50;
            color: white;
        }

        .stock-table tr:hover {
            background-color: #2c2c2c;
        }

        input[type="number"] {
            padding: 8px;
            width: 100px;
            background: #1e1e1e;
            border: 2px solid #4CAF50;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            text-align: center;
        }

        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-size: 16px;
        }

        .alert-success {
            background-color: #1e4620;
            color: #4CAF50;
            border: 1px solid #4CAF50;
        }

        .alert-error {
            background-color: #4a1c1c;
            color: #ff4444;
            border: 1px solid #ff4444;
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
            .stock-container {
                width: 95%;
                padding: 15px;
            }
            
            .filters {
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
        <h1>📦 Управление складом PC Club</h1>
        <nav>
            <ul>
                <li><a href="edit_content.php">📝 Редактирование данных</a></li>
                <li><a href="index.php">🏠 Главная</a></li>
                <li><a href="logout.php">🚪 Выйти</a></li>
            </ul>
        </nav>
    </header>

    <div class="stock-container">
        <form method="get">
            <div class="filters">
                    <select name="table" onchange="this.form.submit()">
                        <option value="gpu" <?= $selected_table == 'gpu' ? 'selected' : '' ?>>🎮 Видеокарты</option>
                        <option value="mcase" <?= $selected_table == 'mcase' ? 'selected' : '' ?>>🖥️ Корпуса</option>
                        <option value="motherboard" <?= $selected_table == 'motherboard' ? 'selected' : '' ?>>🔌 Материнские платы</option>
                        <option value="powerunit" <?= $selected_table == 'powerunit' ? 'selected' : '' ?>>🔋 Блоки питания</option>
                        <option value="processor" <?= $selected_table == 'processor' ? 'selected' : '' ?>>⚡ Процессоры</option>
                        <option value="ram" <?= $selected_table == 'ram' ? 'selected' : '' ?>>💾 Оперативная память</option>
                        <option value="storage" <?= $selected_table == 'storage' ? 'selected' : '' ?>>💽 Накопители</option>
                    </select>

                <div class="search-group">
                    <input type="text" name="search" placeholder="🔍 Поиск по названию..." value="<?= htmlspecialchars($search_query) ?>">
                    <button type="submit">Найти</button>
                </div>
            </div>
        </form>

        <?php if(isset($_SESSION['stock_message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['stock_message'] ?></div>
            <?php unset($_SESSION['stock_message']); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['stock_error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['stock_error'] ?></div>
            <?php unset($_SESSION['stock_error']); ?>
        <?php endif; ?>

        <table class="stock-table">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Текущий запас</th>
                    <th>Новое количество</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $name_column = match($selected_table) {
                    'gpu' => 'gpu_name',
                    'mcase' => 'case_name',
                    'motherboard' => 'motherboard_name',
                    'powerunit' => 'power_name',
                    'processor' => 'unit_name',
                    'ram' => 'ram_name',
                    'storage' => 'storage_name',
                    default => 'name'
                };

                $sql = "SELECT * FROM $selected_table WHERE $name_column LIKE ?";
                $search_term = "%$search_query%";
                $stmt = $link->prepare($sql);
                
                if ($stmt) {
                    $stmt->bind_param("s", $search_term);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        $id_column = $selected_table . '_id';
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row[$name_column] ?? 'N/A') . '</td>';
                        echo '<td>' . ($row['stock'] ?? 0) . '</td>';
                        echo '<td>';
                        echo '<form method="POST">';
                        echo '<input type="number" name="stock" value="' . ($row['stock'] ?? 0) . '" min="0" required>';
                        echo '<input type="hidden" name="table" value="' . htmlspecialchars($selected_table) . '">';
                        echo '<input type="hidden" name="id" value="' . ($row[$id_column] ?? '') . '">';
                        echo '</td>';
                        echo '<td>';
                        echo '<button type="submit" name="update_stock">Обновить</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">Ошибка выполнения запроса</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <footer>
        © <?= date('Y') ?> PC Club | Все права защищены
    </footer>
</body>
</html>
<?php mysqli_close($link); ?>