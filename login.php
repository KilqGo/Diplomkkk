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

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['adminflag'] == 1) {
        header("Location: edit_content.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Обработка входа
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT id, password, adminflag FROM users WHERE login = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['adminflag'] = $user['adminflag'];
                if ($user['adminflag'] == 1) {
                    header("Location: edit_content.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                $error = "Неверный пароль.";
            }
        } else {
            $error = "Пользователь не найден.";
        }
        mysqli_stmt_close($stmt);
    } elseif (isset($_POST['register'])) {
        // Обработка регистрации
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Проверка существования пользователя
        $check_sql = "SELECT id FROM users WHERE login = ?";
        $stmt = mysqli_prepare($link, $check_sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $check_result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($check_result) > 0) {
            $error = "Пользователь с таким логином уже существует.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_sql = "INSERT INTO users (login, password, adminflag) VALUES (?, ?, 0)";
            $stmt = mysqli_prepare($link, $insert_sql);
            mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);
            if (mysqli_stmt_execute($stmt)) {
                $error = "Регистрация успешна. Теперь вы можете войти.";
            } else {
                $error = "Ошибка регистрации: " . mysqli_error($link);
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход и регистрация</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        h2 {
            color: #ffffff;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            background-color: #2c2c2c;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #ffffff;
        }

        input[type="text"],
        input[type="password"] {
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
            margin-bottom: 10px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        p.error {
            color: #ff6666;
            margin-bottom: 15px;
            text-align: center;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Вход и регистрация</h2>

    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="username">Имя пользователя:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" name="login" value="Войти">
        <input type="submit" name="register" value="Зарегистрироваться">
    </form>

    <a href="index.php">Вернуться на главную</a>
</body>
</html>