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

// Проверяем, авторизован ли пользователь как администратор
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

$table = $_GET['table'] ?? '';

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
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить запись</title>
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
        <h1>Добавить запись в таблицу <?php echo htmlspecialchars($table); ?></h1>
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="edit_content.php">Редактировать контент</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php if ($table): ?>
            <form method="post">
                <?php
                // Получение структуры таблицы
                $sql = "SHOW COLUMNS FROM $table";
                $result = $link->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $column = $row['Field'];
                        if ($column !== getIdColumn($table)) { // Исключение ID
                            echo "<label for='$column'>$column:</label>";
                            echo "<input type='text' id='$column' name='$column'><br><br>";
                        }
                    }
                }
                ?>
                <input type="submit" value="Добавить">
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Сбор данных для вставки
                $columns = array();
                $values = array();
                $placeholders = array();
                $types = "";
                $bind_values = array();

                $sql_columns = "SHOW COLUMNS FROM $table";
                $result_columns = $link->query($sql_columns);

                while ($row = $result_columns->fetch_assoc()) {
                    $column = $row['Field'];
                    if ($column !== getIdColumn($table)) {
                        $columns[] = $column;
                        $values[] = $_POST[$column];
                        $placeholders[] = "?";
                        $types .= "s";
                        $bind_values[] = &$_POST[$column];
                    }
                }

                $columns_string = implode(", ", $columns);
                $placeholders_string = implode(", ", $placeholders);

                // SQL-запрос
                $sql = "INSERT INTO $table ($columns_string) VALUES ($placeholders_string)";
                $stmt = $link->prepare($sql);

                $stmt->bind_param($types, ...$bind_values);

                // Запрос
                if ($stmt->execute()) {
                    echo "<p>Запись успешно добавлена в таблицу $table.</p>";
                } else {
                    echo "<p>Ошибка добавления записи: " . $stmt->error . "</p>";
                }

                $stmt->close();
            }
            ?>
        <?php else: ?>
            

Выберите таблицу для добавления записи.

        <?php endif; ?>
    </main>

    <footer>
        © 2025 PC Club
    </footer>

</body>
</html>

<?php
mysqli_close($link);
?>
