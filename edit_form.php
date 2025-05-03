<?php
session_start();
require_once 'validator.php';
$validationRules = require 'validation_rules.php';

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

$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? 0;

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

$idColumn = getIdColumn($table);
$stmt = $link->prepare("SELECT * FROM $table WHERE $idColumn = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Запись не найдена.");
}

$row = $result->fetch_assoc();

$validator = new validator($link, $validationRules);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    
    if (isset($validationRules[$table])) {
        $errors = $validator->validate($_POST, $table);
    }

    if (empty($errors)) {
        try {
            $updates = [];
            $values = [];
            $types = "";
            
            foreach ($_POST as $column => $value) {
                if ($column !== $idColumn) {
                    $updates[] = "$column = ?";
                    $values[] = $value;
                    $types .= "s";
                }
            }
            
            $sql = "UPDATE $table SET ".implode(', ', $updates)." WHERE $idColumn = ?";
            $values[] = $id;
            $types .= "i";
            
            $stmt = $link->prepare($sql);
            $stmt->bind_param($types, ...$values);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Запись успешно обновлена";
                header("Location: edit_content.php");
                exit;
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            $errors[] = "Ошибка базы данных: " . $e->getMessage();
        }
    }
    
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = $_POST;
    header("Location: edit_form.php?table=$table&id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование</title>
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

        .form-title {
            color: #4CAF50;
            text-align: center;
            margin-bottom: 30px;
        }

        .data-form {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #ffffff;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #4CAF50;
            border-radius: 6px;
            background-color: #1e1e1e;
            color: white;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: #45a049;
            outline: none;
        }

        .error-field {
            border-color: #ff4444 !important;
        }

        .field-error {
            color: #ff4444;
            margin-top: 5px;
            font-size: 14px;
        }

        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-size: 16px;
        }

        .alert-error {
            background-color: #4a1c1c;
            color: #ff4444;
            border: 1px solid #ff4444;
        }

        button[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
            transform: translateY(-2px);
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
        }
    </style>
</head>
<body>
    <header>
        <h1>✏️ Редактирование записи</h1>
        <nav>
            <ul>
                <li><a href="stock_management.php">📦 Склад</a></li>
                <li><a href="edit_content.php">📝 Админ панель</a></li>
                <li><a href="index.php">🏠 Главная</a></li>
                <li><a href="order_form.php">🛒 Оформить заказ</a></li>
                <li><a href="logout.php">🚪 Выйти</a></li>
            </ul>
        </nav>
    </header>

    <div class="main-container">
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert alert-error">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <div>❌ <?= $error ?></div>
                <?php endforeach; ?>
                <?php unset($_SESSION['errors']); unset($_SESSION['old']); ?>
            </div>
        <?php endif; ?>

        <h2 class="form-title">Редактирование в таблице: <?= htmlspecialchars($table) ?></h2>
        
        <form class="data-form" method="post">
            <?php foreach ($row as $column => $value): ?>
                <?php if ($column !== $idColumn): ?>
                    <div class="form-group">
                        <label><?= $column ?></label>
                        <input type="text" 
                               name="<?= $column ?>" 
                               value="<?= htmlspecialchars($_SESSION['old'][$column] ?? $value) ?>"
                               class="<?= isset($_SESSION['errors'][$column]) ? 'error-field' : '' ?>">
                        <?php if (isset($_SESSION['errors'][$column])): ?>
                            <div class="field-error"><?= $_SESSION['errors'][$column] ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <button type="submit">💾 Сохранить изменения</button>
        </form>
    </div>

    <footer>
        © <?= date('Y') ?> PC Club | Все права защищены
    </footer>
</body>
</html>
<?php mysqli_close($link); ?>