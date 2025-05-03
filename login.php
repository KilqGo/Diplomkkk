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
    <title>Авторизация - PC Club</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
         header h1 {
        color: white !important; }

        .auth-container {
            max-width: 400px;
            margin: 3rem auto;
            padding: 2rem;
            background: var(--secondary-bg);
            border-radius: var(--border-radius);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .auth-title {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--accent-color);
        }
        
        .auth-switch {
            text-align: center;
            margin-top: 1.5rem;
        }

        .auth-container .form-group {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .auth-container button[type="submit"] {
            flex: 1;
            padding: 12px;
            font-size: 16px;
        }

        .auth-switch a {
            color: var(--accent-color) !important;
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .auth-switch a:visited {
            color: var(--accent-color) !important;
        }

        .auth-switch a:hover {
            opacity: 0.8;
        }

        @media (max-width: 480px) {
            .auth-container .form-group {
                flex-direction: column;
            }
        }
    </style>

</head>
<body>
    <header>
        <h1>🔐 Авторизация PC Club</h1>
        <nav>
            <ul>
                <li><a href="index.php">🏠 Главная</a></li>
                <li><a href="order_form.php">🛒 Заказы</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="auth-container">
            <h2 class="auth-title">Вход / Регистрация</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    ⚠️ <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label>Имя пользователя</label>
                    <input type="text" 
                           name="username" 
                           required
                           placeholder="Введите ваш логин">
                </div>

                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" 
                           name="password" 
                           required
                           placeholder="••••••••">
                </div>

                <div class="form-group">
                    <button type="submit" name="login">🚪 Войти</button>
                    <button type="submit" name="register">📝 Зарегистрироваться</button>
                </div>
            </form>

            <div class="auth-switch">
                <a href="index.php">← Вернуться на главную</a>
            </div>
        </div>
    </main>

    <footer>
        © <?= date('Y') ?> PC Club | Все права защищены
    </footer>
</body>
</html>