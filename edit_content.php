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
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование содержимого</title>
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

        table {
            width: 80%; 
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #2c2c2c;
            border-radius: 8px;
            margin-left: auto; 
            margin-right: auto;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 5px; 
            text-align: left;
        }

        th {
            background-color: #1e1e1e;
        }

        button, .button {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        font-size: 12px;
        min-width: 80px;
        text-align: center;
        text-decoration: none; 
        display: inline-block; 
        }

        button:hover, .button:hover {
            background-color: #45a049;
        }

        .table-container {
            margin-bottom: 20px;
            background-color: #2c2c2c;
            border-radius: 8px;
            padding: 15px;
        }

        .table-container button {
            margin-bottom: 10px;
            width: auto;
            display: block;
        }

        .hidden {
            display: none;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #1e1e1e;
            color: white;
            margin-top: auto; 
        }
    </style>
</head>
<body>

    <header>
        <h1>Редактирование содержимого</h1>
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="order_form.php">Оформить заказ</a></li>
                <li><a href="logout.php">Выйти</a></li>
            </ul>
        </nav>
    </header>

    <div class="table-container">
        <button onclick="toggleTable('assembly')">Таблица Сборка</button>
        <a href="add_data.php?table=assembly" class="button">Добавить запись</a>
        <table id="assembly" class="hidden">
            <tr>
                <th>Id</th>
                <th>Цена</th>
                <th>Дата составления</th>
                <th>Дата доставки</th>
                <th>Адрес доставки</th>
                <th>Id клиента</th>
                <th>Id мастера</th>
                <th>GPU ID</th>
                <th>Case ID</th>
                <th>Motherboard ID</th>
                <th>Power Unit ID</th>
                <th>Processor ID</th>
                <th>RAM ID</th>
                <th>Storage ID</th>
                <th>Действия</th>
            </tr>
            <?php
            $sql = "SELECT * FROM assembly";
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['assembly_order_id']}</td>";
                    echo "<td>{$row['assembly_price']}</td>";
                    echo "<td>{$row['date_of_admission']}</td>";
                    echo "<td>{$row['date_of_delivery']}</td>";
                    echo "<td>{$row['delivery_address']}</td>";
                    echo "<td>{$row['ctr_id']}</td>";
                    echo "<td>{$row['mtr_id']}</td>";
                    echo "<td>{$row['gpu_id']}</td>";
                    echo "<td>{$row['cse_id']}</td>";
                    echo "<td>{$row['mbd_id']}</td>";
                    echo "<td>{$row['psu_id']}</td>";
                    echo "<td>{$row['cpu_id']}</td>";
                    echo "<td>{$row['ram_id']}</td>";
                    echo "<td>{$row['sdu_id']}</td>";
                    echo "<td>
                            <button onclick='editData(\"assembly\", {$row['assembly_order_id']})'>Редактировать</button>
                            <button onclick='deleteData(\"assembly\", {$row['assembly_order_id']})'>Удалить</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='15'>Нет данных</td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="table-container">
        <button onclick="toggleTable('master')">Таблица Мастер</button>
        <a href="add_data.php?table=master" class="button">Добавить запись</a>
        <table id="master" class="hidden">
            <tr>
                <th>Id</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Адрес</th>
                <th>Действия</th>
            </tr>
            <?php
            $sql = "SELECT * FROM master";
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['mtr_id']}</td>";
                    echo "<td>{$row['full_name']}</td>";
                    echo "<td>{$row['phone_number']}</td>";
                    echo "<td>{$row['legal_address']}</td>";
                    echo "<td>
                            <button onclick='editData(\"master\", {$row['mtr_id']})'>Редактировать</button>
                            <button onclick='deleteData(\"master\", {$row['mtr_id']})'>Удалить</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Нет данных</td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="table-container">
        <button onclick="toggleTable('customer')">Таблица Заказчик</button>
        <a href="add_data.php?table=customer" class="button">Добавить запись</a>
        <table id="customer" class="hidden">
            <tr>
                <th>Id</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Адрес</th>
                <th>Действия</th>
            </tr>
            <?php
            $sql = "SELECT * FROM customer";
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['ctr_id']}</td>";
                    echo "<td>{$row['full_name']}</td>";
                    echo "<td>{$row['phone_number']}</td>";
                    echo "<td>{$row['legal_address']}</td>";
                    echo "<td>
                            <button onclick='editData(\"customer\", {$row['ctr_id']})'>Редактировать</button>
                            <button onclick='deleteData(\"customer\", {$row['ctr_id']})'>Удалить</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Нет данных</td></tr>";
            }
        ?>
        </table>
    </div>

    <div class="table-container">
        <button onclick="toggleTable('gpu')">Таблица GPU</button>
        <a href="add_data.php?table=gpu" class="button">Добавить запись</a>
        <table id="gpu" class="hidden">
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Объем памяти</th>
                <th>Серия</th>
                <th>Производитель</th>
                <th>Цена</th>
                <th>Действия</th>
            </tr>
            <?php
            $sql = "SELECT * FROM gpu";
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['gpu_id']}</td>";
                    echo "<td>{$row['gpu_name']}</td>";
                    echo "<td>{$row['gmemory_size']}</td>";
                    echo "<td>{$row['gpu_series']}</td>";
                    echo "<td>{$row['gpu_manufacturer']}</td>";
                    echo "<td>{$row['price']}</td>";
                    echo "<td>
                            <button onclick='editData(\"gpu\", {$row['gpu_id']})'>Редактировать</button>
                            <button onclick='deleteData(\"gpu\", {$row['gpu_id']})'>Удалить</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Нет данных</td></tr>";
            }
        ?>
    </table>
</div>

<div class="table-container">
    <button onclick="toggleTable('mcase')">Таблица Case</button>
    <a href="add_data.php?table=mcase" class="button">Добавить запись</a>
    <table id="mcase" class="hidden">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Форм-фактор</th>
            <th>Размер</th>
            <th>Производитель</th>
            <th>Цена</th>
            <th>Действия</th>
        </tr>
        <?php
        $sql = "SELECT * FROM mcase";
        $result = $link->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['cse_id']}</td>";
                echo "<td>{$row['case_name']}</td>";
                echo "<td>{$row['form_factor']}</td>";
                echo "<td>{$row['case_size']}</td>";
                echo "<td>{$row['case_manufacturer']}</td>";
                echo "<td>{$row['price']}</td>";
                echo "<td>
                        <button onclick='editData(\"mcase\", {$row['cse_id']})'>Редактировать</button>
                        <button onclick='deleteData(\"mcase\", {$row['cse_id']})'>Удалить</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Нет данных</td></tr>";
        }
        ?>
    </table>
</div>

<div class="table-container">
    <button onclick="toggleTable('motherboard')">Таблица Motherboard</button>
    <a href="add_data.php?table=motherboard" class="button">Добавить запись</a>
    <table id="motherboard" class="hidden">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Форм-фактор</th>
            <th>Чипсет</th>
            <th>Сокет</th>
            <th>Производитель</th>
            <th>Цена</th>
            <th>Действия</th>
        </tr>
        <?php
        $sql = "SELECT * FROM motherboard";
        $result = $link->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['mbd_id']}</td>";
                echo "<td>{$row['motherboard_name']}</td>";
                echo "<td>{$row['form_factor']}</td>";
                echo "<td>{$row['chipset']}</td>";
                echo "<td>{$row['socket']}</td>";
                echo "<td>{$row['board_manufacturer']}</td>";
                echo "<td>{$row['price']}</td>";
                echo "<td>
                        <button onclick='editData(\"motherboard\", {$row['mbd_id']})'>Редактировать</button>
                        <button onclick='deleteData(\"motherboard\", {$row['mbd_id']})'>Удалить</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Нет данных</td></tr>";
        }
        ?>
    </table>
</div>

<div class="table-container">
    <button onclick="toggleTable('powerunit')">Таблица Power Unit</button>
    <a href="add_data.php?table=powerunit" class="button">Добавить запись</a>
    <table id="powerunit" class="hidden">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Мощность</th>
            <th>Производитель</th>
            <th>Цена</th>
            <th>Действия</th>
        </tr>
        <?php
        $sql = "SELECT * FROM powerunit";
        $result = $link->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['psu_id']}</td>";
                echo "<td>{$row['power_name']}</td>";
                echo "<td>{$row['capability']}</td>";
                echo "<td>{$row['power_manufacturer']}</td>";
                echo "<td>{$row['price']}</td>";
                echo "<td>
                        <button onclick='editData(\"powerunit\", {$row['psu_id']})'>Редактировать</button>
                        <button onclick='deleteData(\"powerunit\", {$row['psu_id']})'>Удалить</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Нет данных</td></tr>";
        }
        ?>
    </table>
</div>

<div class="table-container">
    <button onclick="toggleTable('processor')">Таблица Processor</button>
    <a href="add_data.php?table=processor" class="button">Добавить запись</a>
    <table id="processor" class="hidden">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Сокет</th>
            <th>Базовая частота</th>
            <th>Количество ядер</th>
            <th>Производитель</th>
            <th>Цена</th>
            <th>Действия</th>
        </tr>
        <?php
        $sql = "SELECT * FROM processor";
        $result = $link->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['cpu_id']}</td>";
                echo "<td>{$row['unit_name']}</td>";
                echo "<td>{$row['socket']}</td>";
                echo "<td>{$row['base_frequency']}</td>";
                echo "<td>{$row['number_of_cores']}</td>";
                echo "<td>{$row['cpu_manufacturer']}</td>";
                echo "<td>{$row['price']}</td>";
                echo "<td>
                        <button onclick='editData(\"processor\", {$row['cpu_id']})'>Редактировать</button>
                        <button onclick='deleteData(\"processor\", {$row['cpu_id']})'>Удалить</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Нет данных</td></tr>";
        }
        ?>
    </table>
</div>

<div class="table-container">
    <button onclick="toggleTable('ram')">Таблица RAM</button>
    <a href="add_data.php?table=ram" class="button">Добавить запись</a>
    <table id="ram" class="hidden">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Объем памяти</th>
            <th>Тип</th>
            <th>Базовая частота</th>
            <th>Производитель</th>
            <th>Цена</th>
            <th>Действия</th>
        </tr>
        <?php
        $sql = "SELECT * FROM ram";
        $result = $link->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['ram_id']}</td>";
                echo "<td>{$row['ram_name']}</td>";
                echo "<td>{$row['memory_size']}</td>";
                echo "<td>{$row['type']}</td>";
                echo "<td>{$row['base_frequency']}</td>";
                echo "<td>{$row['ram_manufacturer']}</td>";
                echo "<td>{$row['price']}</td>";
                echo "<td>
                        <button onclick='editData(\"ram\", {$row['ram_id']})'>Редактировать</button>
                        <button onclick='deleteData(\"ram\", {$row['ram_id']})'>Удалить</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Нет данных</td></tr>";
        }
        ?>
    </table>
</div>

<div class="table-container">
    <button onclick="toggleTable('storage')">Таблица Storage</button>
    <a href="add_data.php?table=storage" class="button">Добавить запись</a>
    <table id="storage" class="hidden">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Объем</th>
            <th>Скорость чтения</th>
            <th>Тип</th>
            <th>Производитель</th>
            <th>Цена</th>
            <th>Действия</th>
        </tr>
        <?php
        $sql = "SELECT * FROM storage";
        $result = $link->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['sdu_id']}</td>";
                echo "<td>{$row['storage_name']}</td>";
                echo "<td>{$row['storage_capacity']}</td>";
                echo "<td>{$row['reading_speed']}</td>";
                echo "<td>{$row['sdu_type']}</td>";
                echo "<td>{$row['sdu_manufacturer']}</td>";
                echo "<td>{$row['price']}</td>";
                echo "<td>
                        <button onclick='editData(\"storage\", {$row['sdu_id']})'>Редактировать</button>
                        <button onclick='deleteData(\"storage\", {$row['sdu_id']})'>Удалить</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Нет данных</td></tr>";
        }
        ?>
    </table>
</div>

<script>
    function toggleTable(tableId) {
        var table = document.getElementById(tableId);
        if (table.classList.contains('hidden')) {
            table.classList.remove('hidden');
        } else {
            table.classList.add('hidden');
        }
    }

    function editData(table, id) {
        // Страница редактирования
        window.location.href = 'edit_form.php?table=' + table + '&id=' + id;
    }

    function deleteData(table, id) {
        if (confirm('Вы уверены, что хотите удалить эту запись?')) {
            // Удаление данных
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_data.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert(xhr.responseText); 
                    location.reload(); 
                } else {
                    alert('Произошла ошибка при удалении.');
                }
            };
            xhr.send('table=' + table + '&id=' + id);
        }
    }
</script>

<footer>
    © 2025 PC Club
</footer>

</body>
</html>

<?php
mysqli_close($link);
?>
