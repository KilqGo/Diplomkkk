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

$validator = new validator($link, $validationRules);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    
    if (isset($validationRules[$table])) {
        $errors = $validator->validate($_POST, $table);
    }

    if (empty($errors)) {
        $columns = [];
        $values = [];
        $placeholders = [];
        $types = "";
        
        foreach ($_POST as $column => $value) {
            if ($column !== getIdColumn($table)) {
                $columns[] = $column;
                $values[] = $value;
                $placeholders[] = "?";
                $types .= "s";
            }
        }
        
        try {
            $sql = "INSERT INTO $table (".implode(', ', $columns).") VALUES (".implode(', ', $placeholders).")";
            $stmt = $link->prepare($sql);
            $stmt->bind_param($types, ...$values);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Запись успешно добавлена";
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
    header("Location: add_data.php?table=$table");
    exit;
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
    <?php if (isset($_SESSION['errors'])): ?>
        <div class="error-container">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <div class="error"><?= $error ?></div>
            <?php endforeach; ?>
            <?php unset($_SESSION['errors']); unset($_SESSION['old']); ?>
        </div>
    <?php endif; ?>

    <header>
        <h1>Добавить запись в <?= htmlspecialchars($table) ?></h1>
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="edit_content.php">Редактировать</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php if ($table): ?>
            <form method="post">
                <?php
                $result = $link->query("SHOW COLUMNS FROM $table");
                while ($row = $result->fetch_assoc()):
                    $column = $row['Field'];
                    if ($column !== getIdColumn($table)):
                ?>
                    <div class="form-group">
                        <label><?= $column ?></label>
                        <input type="text" 
                               name="<?= $column ?>" 
                               value="<?= htmlspecialchars($_SESSION['old'][$column] ?? '') ?>"
                               class="<?= isset($_SESSION['errors'][$column]) ? 'error-field' : '' ?>">
                        <?php if (isset($_SESSION['errors'][$column])): ?>
                            <div class="field-error"><?= $_SESSION['errors'][$column] ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; endwhile; ?>
                <input type="submit" value="Добавить">
            </form>
        <?php endif; ?>
    </main>
</body>
</html>
<?php mysqli_close($link); ?>