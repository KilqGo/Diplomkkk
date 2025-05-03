<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'web_magazin';

$link = mysqli_connect($host, $user, $password, $db_name);

if (!$link) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

function getComponents($link, $table, $id_col, $name_col, $check_stock = false) {
    $data = [];
    $sql = $check_stock 
        ? "SELECT $id_col, $name_col, stock FROM $table WHERE stock > 0"
        : "SELECT $id_col, $name_col FROM $table";
    
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $item = ['name' => $row[$name_col]];
            if ($check_stock) $item['stock'] = $row['stock'];
            $data[$row[$id_col]] = $item;
        }
    }
    return $data;
}

$customers = getComponents($link, 'customer', 'ctr_id', 'full_name');
$masters = getComponents($link, 'master', 'mtr_id', 'full_name');

$components = [
    'gpu' => getComponents($link, 'gpu', 'gpu_id', 'gpu_name', true),
    'mcase' => getComponents($link, 'mcase', 'cse_id', 'case_name', true),
    'motherboard' => getComponents($link, 'motherboard', 'mbd_id', 'motherboard_name', true),
    'powerunit' => getComponents($link, 'powerunit', 'psu_id', 'power_name', true),
    'processor' => getComponents($link, 'processor', 'cpu_id', 'unit_name', true),
    'ram' => getComponents($link, 'ram', 'ram_id', 'ram_name', true),
    'storage' => getComponents($link, 'storage', 'sdu_id', 'storage_name', true)
];

$errors = [];
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Валидация данных
    $assembly_price = $_POST["assembly_price"];
    if (strlen($assembly_price) > 6) {
        $errors['assembly_price'] = "Цена должна содержать не более 6 символов.";
    } elseif (!is_numeric($assembly_price) || $assembly_price < 0) {
        $errors['assembly_price'] = "Цена должна быть неотрицательным числом.";
    }

    $delivery_address = $_POST["delivery_address"];
    if (strlen($delivery_address) > 110) {
        $errors['delivery_address'] = "Адрес доставки не должен превышать 110 символов.";
    }

    $date_of_admission = $_POST["date_of_admission"];
    $date_of_delivery = $_POST["date_of_delivery"];
    
    if (strtotime($date_of_admission) > time()) {
        $errors['date_of_admission'] = "Дата оформления не может быть в будущем.";
    }
    
    if (strtotime($date_of_delivery) < strtotime($date_of_admission)) {
        $errors['date_of_delivery'] = "Дата доставки должна быть позже даты оформления.";
    }

    $component_ids = [
        'gpu_id' => $_POST['gpu_id'],
        'cse_id' => $_POST['cse_id'],
        'mbd_id' => $_POST['mbd_id'],
        'psu_id' => $_POST['psu_id'],
        'cpu_id' => $_POST['cpu_id'],
        'ram_id' => $_POST['ram_id'],
        'sdu_id' => $_POST['sdu_id']
    ];

    if (empty($errors)) {
        mysqli_autocommit($link, false);
        
        try {
            // Проверка остатков компонентов
            foreach ($component_ids as $field => $id) {
                $table = get_table_name($field);
                $stmt = $link->prepare("SELECT stock FROM $table WHERE ".get_id_column($table)." = ? FOR UPDATE");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if (!$result || $result->num_rows === 0) {
                    throw new Exception("Компонент $table не найден");
                }
                
                $row = $result->fetch_assoc();
                if ($row['stock'] < 1) {
                    throw new Exception("Недостаточно $table на складе");
                }
            }

            // Добавление заказа
            $stmt = $link->prepare("INSERT INTO assembly (
                assembly_price, date_of_admission, date_of_delivery, delivery_address,
                ctr_id, mtr_id, gpu_id, cse_id, mbd_id, psu_id, cpu_id, ram_id, sdu_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->bind_param("dsssiiiiiiiii",
                $_POST['assembly_price'],
                $_POST['date_of_admission'],
                $_POST['date_of_delivery'],
                $_POST['delivery_address'],
                $_POST['ctr_id'],
                $_POST['mtr_id'],
                $_POST['gpu_id'],
                $_POST['cse_id'],
                $_POST['mbd_id'],
                $_POST['psu_id'],
                $_POST['cpu_id'],
                $_POST['ram_id'],
                $_POST['sdu_id']
            );

            if (!$stmt->execute()) throw new Exception("Ошибка оформления: ".$stmt->error);

            // Обновление остатков
            foreach ($component_ids as $field => $id) {
                $table = get_table_name($field);
                $stmt = $link->prepare("UPDATE $table SET stock = stock - 1 WHERE ".get_id_column($table)." = ?");
                $stmt->bind_param("i", $id);
                if (!$stmt->execute()) throw new Exception("Ошибка обновления: ".$stmt->error);
            }

            mysqli_commit($link);
            $success_message = "Заказ успешно оформлен!";
        } catch (Exception $e) {
            mysqli_rollback($link);
            $errors[] = $e->getMessage();
        } finally {
            mysqli_autocommit($link, true);
        }
    }
}

function get_id_column($table) {
    $columns = [
        'gpu' => 'gpu_id',
        'mcase' => 'cse_id',
        'motherboard' => 'mbd_id',
        'powerunit' => 'psu_id',
        'processor' => 'cpu_id',
        'ram' => 'ram_id',
        'storage' => 'sdu_id'
    ];
    return $columns[$table] ?? 'id';
}

function get_table_name($field) {
    $mapping = [
        'gpu_id' => 'gpu',
        'cse_id' => 'mcase',
        'mbd_id' => 'motherboard',
        'psu_id' => 'powerunit',
        'cpu_id' => 'processor',
        'ram_id' => 'ram',
        'sdu_id' => 'storage'
    ];
    return $mapping[$field] ?? str_replace('_id', '', $field);
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма оформления заказа</title>
    <link rel="stylesheet" href="style.css">
    <style>
         header h1 {
        color: white !important; 
        }
        .dropdown-btn {
            background: none;
            border: none;
            color: #aaa;
            cursor: pointer;
            position: absolute;
            right: 6px;
            top: 8px;
            z-index: 2;
        }
        
        .stock-info {
            float: right;
            font-size: 0.85em;
            color: #888;
        }

        form {
        max-width: 600px;
        margin: 0 auto;
        }

        label {
            display: block;
            margin: 15px 0 8px;
            color: #ddd;
        }

        input:not([type="submit"]), 
        select, 
        .dropdown-field {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            background: #333;
            border: 1px solid #444;
            border-radius: 4px;
            color: #fff;
            box-sizing: border-box;
        }

        .dropdown-field {
            position: relative;
        }

        .dropdown-input {
            padding-right: 30px;
            cursor: pointer;
        }

        input[type="submit"] {
            width: auto;
            padding: 12px 25px;
            margin-top: 20px;
            background: #28a745;
            border: none;
            cursor: pointer;
        }

        .dropdown-list {
            width: calc(100% - 2px); 
            max-width: 100%;
            left: 0;
            right: 0;
            margin-top: -5px;
            box-sizing: border-box;
            overflow-y: auto;
            max-height: 200px;
            white-space: nowrap;
        }

        .dropdown-list li {
            padding: 8px 30px 8px 12px;
            position: relative;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stock-info {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <header>
        <h1>🛒 Оформить заказ</h1>
        <nav>
            <ul>
                <li><a href="index.php">🏠 Главная</a></li>
                <li><a href="edit_content.php">📝 Редактировать контент</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p>⚠️ <?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="success">✅ <?= $success_message ?></div>
        <?php endif; ?>

        <form method="post" autocomplete="off">
            <label for="assembly_price">Цена сборки:</label>
            <input type="number" id="assembly_price" name="assembly_price" 
                   value="<?= htmlspecialchars($_POST['assembly_price'] ?? '') ?>" required>

            <label for="date_of_admission">Дата оформления:</label>
            <input type="date" id="date_of_admission" name="date_of_admission"
                   value="<?= htmlspecialchars($_POST['date_of_admission'] ?? '') ?>" required>

            <label for="date_of_delivery">Дата доставки:</label>
            <input type="date" id="date_of_delivery" name="date_of_delivery" 
                   value="<?= htmlspecialchars($_POST['date_of_delivery'] ?? '') ?>" required>

            <label for="delivery_address">Адрес доставки:</label>
            <input type="text" id="delivery_address" name="delivery_address" 
                   value="<?= htmlspecialchars($_POST['delivery_address'] ?? '') ?>" 
                   maxlength="110" required>

            <label for="ctr_id">Клиент:</label>
            <select id="ctr_id" name="ctr_id" required>
                <option value="">Выберите клиента</option>
                <?php foreach ($customers as $id => $item): ?>
                    <option value="<?= $id ?>" <?= ($_POST['ctr_id'] ?? '') == $id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($item['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="mtr_id">Мастер:</label>
            <select id="mtr_id" name="mtr_id" required>
                <option value="">Выберите мастера</option>
                <?php foreach ($masters as $id => $item): ?>
                    <option value="<?= $id ?>" <?= ($_POST['mtr_id'] ?? '') == $id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($item['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php
            // Массив для полей с поиском
            $component_fields = [
                'gpu_id' => ['label' => 'Видеокарта', 'data' => $components['gpu']],
                'cse_id' => ['label' => 'Корпус', 'data' => $components['mcase']],
                'mbd_id' => ['label' => 'Материнская плата', 'data' => $components['motherboard']],
                'psu_id' => ['label' => 'Блок питания', 'data' => $components['powerunit']],
                'cpu_id' => ['label' => 'Процессор', 'data' => $components['processor']],
                'ram_id' => ['label' => 'Оперативная память', 'data' => $components['ram']],
                'sdu_id' => ['label' => 'Накопитель', 'data' => $components['storage']]
            ];
            foreach ($component_fields as $field => $config):
                $input_value = '';
                // Получить выбранное название по id, если есть
                if (isset($_POST[$field]) && $_POST[$field] && isset($config['data'][$_POST[$field]])) {
                    $input_value = htmlspecialchars($config['data'][$_POST[$field]]['name']);
                }
            ?>
                <label for="<?= $field ?>_input"><?= $config['label'] ?>:</label>
                <div class="dropdown-field">
                    <input type="text"
                        class="dropdown-input"
                        id="<?= $field ?>_input"
                        name="<?= $field ?>_input"
                        placeholder="Начните вводить..."
                        value="<?= $input_value ?>"
                        autocomplete="off"
                        data-dropdown="<?= $field ?>"
                        required
                        style="background:#333;cursor:pointer;"
                    >
                    <button type="button" class="dropdown-btn" tabindex="-1" onclick="toggleDropdown('<?= $field ?>')">&#x25BC;</button>
                    <ul class="dropdown-list" id="<?= $field ?>_list">
                        <li data-value="">Выберите <?= mb_strtolower($config['label']) ?>...</li>
                        <?php foreach ($config['data'] as $id => $item): ?>
                            <li data-value="<?= $id ?>">
                                <?= htmlspecialchars($item['name']) ?>
                                <?php if(isset($item['stock'])): ?>
                                    <span class="stock-info">(Ост: <?= $item['stock'] ?>)</span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <input type="hidden" name="<?= $field ?>" id="<?= $field ?>" value="<?= htmlspecialchars($_POST[$field] ?? '') ?>">
                </div>
            <?php endforeach; ?>

            <input type="submit" value="Оформить заказ">
        </form>
    </main>

    <footer>
        © <?= date('Y') ?> PC Club | Все права защищены
    </footer>
    <script>
        document.querySelectorAll('.dropdown-input').forEach(function(input) {
            input.addEventListener('focus', function() {
                showDropdown(this.dataset.dropdown);
            });
            input.addEventListener('input', function() {
                filterDropdown(this.dataset.dropdown, this.value);
            });
        });

        // Открытие по кнопке
        function toggleDropdown(field) {
            var list = document.getElementById(field + '_list');
            list.classList.toggle('show');
            document.getElementById(field + '_input').focus();
        }
        function showDropdown(field) {
            document.querySelectorAll('.dropdown-list').forEach(function(list) {
                list.classList.remove('show');
            });
            document.getElementById(field + '_list').classList.add('show');
        }

        // Фильтрация списка
        function filterDropdown(field, value) {
            var list = document.getElementById(field + '_list');
            var filter = value.toLowerCase();
            list.querySelectorAll('li').forEach(function(li, idx) {
                if (idx === 0) return; 
                li.style.display = li.textContent.toLowerCase().includes(filter) ? '' : 'none';
            });
        }

        // Выбор элемента
        document.querySelectorAll('.dropdown-list').forEach(function(list) {
            list.addEventListener('click', function(e) {
                if (e.target.tagName.toLowerCase() === 'li') {
                    var val = e.target.getAttribute('data-value');
                    var field = this.id.replace('_list','');
                    var input = document.getElementById(field + '_input');
                    var hidden = document.getElementById(field);
                    if (val) {
                        input.value = e.target.childNodes[0].textContent.trim();
                        hidden.value = val;
                    } else {
                        input.value = '';
                        hidden.value = '';
                    }
                    this.classList.remove('show');
                }
            });
        });

        // Закрытие списка клик
        document.addEventListener('click', function(e) {
            if (!e.target.classList.contains('dropdown-input') && !e.target.classList.contains('dropdown-btn')) {
                document.querySelectorAll('.dropdown-list').forEach(function(list) {
                    list.classList.remove('show');
                });
            }
        });
    </script>
</body>
</html>
<?php
mysqli_close($link);
?>