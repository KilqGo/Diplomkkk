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

// Администратор
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? 0;

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

// Получаем данные из бд
$idColumn = getIdColumn($table);
$sql = "SELECT * FROM $table WHERE $idColumn = ?";
$stmt = $link->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Запись не найдена.");
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование данных</title>
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
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin-right: 20px;
        }

        nav ul li a {
            color: #4CAF50;
            text-decoration: none;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        main {
            flex: 1; 
            padding: 20px;
        }

        form {
            width: 80%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #2c2c2c;
            padding: 20px;
            border-radius: 8px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #ffffff;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #333;
            color: #ffffff;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            color: #4CAF50;
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #1e1e1e;
            color: white;
            margin-top: auto;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <header>
        <h1>Редактирование данных</h1>
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="edit_content.php">Редактировать контент</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <form method="post">
            <?php foreach ($row as $column => $value): ?>
                <?php if ($column !== 'table' && $column !== 'id'): ?>
                    <label for="<?php echo $column; ?>"><?php echo $column; ?>:</label>
                    <input type="text" id="<?php echo $column; ?>" name="<?php echo $column; ?>" value="<?php echo htmlspecialchars($value); ?>">
                <?php endif; ?>
            <?php endforeach; ?>
            <input type="submit" value="Сохранить">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $host = 'localhost';
            $user = 'root';
            $password = '';
            $db_name = 'web_magazin';

            $link = mysqli_connect($host, $user, $password, $db_name);

            if (!$link) {
                die("Ошибка подключения: " . mysqli_connect_error());
            }

            $idColumn = getIdColumn($table);
            $sql = "UPDATE $table SET ";
            $updates = array();
            $types = "";
            $values = array();
            foreach ($_POST as $column => $value) {
                if ($column != 'table' && $column != 'id') {
                    $updates[] = "$column = ?";
                    $types .= "s"; 
                    $values[] = $value;
                }
            }
            $sql .= implode(', ', $updates);
            $sql .= " WHERE $idColumn = ?";

            // Подготовка запроса
            $stmt = $link->prepare($sql);

            // Добавляем ID в массив значений
            $values[] = $id;
            $types .= "i"; 

            // Формируем строку типов для bind_param
            $typesString = str_repeat("s", count($values) - 1) . "i";

            // Привязываем параметры
            $stmt->bind_param($typesString, ...$values);


            if ($stmt->execute()) {
                echo "<p>Запись успешно обновлена.</p>";
            } else {
                echo "<p>Ошибка обновления записи: " . $stmt->error . "</p>";
            }

            $stmt->close();
            mysqli_close($link);
        }
        ?>
    </main>

    <a href="edit_content.php" class="back-link">Вернуться к редактированию контента</a>

    <footer>
        © 2025 PC Club
    </footer>

</body>
</html>
