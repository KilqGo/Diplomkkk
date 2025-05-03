<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>PC Club - Сборка компьютеров</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        header h1 {
        color: white !important; 
        }
        .top-blocks {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 7rem;
        }

        .horizontal-scroll {
            overflow-x: auto;
            padding: 1rem 0;
            scrollbar-width: thin; 
            scrollbar-color: var(--accent-color) var(--secondary-bg);
        }
        .horizontal-scroll::-webkit-scrollbar {
            height: 8px;
        }
        .horizontal-scroll::-webkit-scrollbar-track {
            background: var(--secondary-bg);
            border-radius: 4px;
        }
        .horizontal-scroll::-webkit-scrollbar-thumb {
            background-color: var(--accent-color);
            border-radius: 4px;
        }

        .horizontal-scroll__content {
            display: flex;
            gap: 1.5rem;
            min-width: max-content;
            padding: 0 2rem;
        }

        .horizontal-scroll__content img {
            width: 320px;
            height: 220px;
            object-fit: cover;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .horizontal-scroll__content img:hover {
            transform: translateY(-5px);
        }

        .btn {
            display: inline-block;
            background-color: var(--accent-color);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s ease;
            margin-top: auto;
        }
        .btn:hover {
            background-color: #0056b3;
        }

        .card {
            background-color: var(--secondary-bg);
            border-radius: var(--border-radius);
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        @media (max-width: 768px) {
            .top-blocks {
                grid-template-columns: 1fr;
                margin-bottom: 2rem;
            }
            .horizontal-scroll__content img {
                width: 240px;
                height: 160px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>🖥️ PC Club</h1>
        <nav>
            <ul>
                <li><a href="edit_content.php" class="nav-link">📝 Админ панель</a></li>
                <li><a href="order_form.php" class="nav-link">🛒 Оформить заказ</a></li>
                <li><a href="logout.php" class="nav-link">🔁 Смена учетной записи</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="top-blocks">
            <section class="card">
                <h2>🚀 Собери свой компьютер</h2>
                <p>Выберите компоненты и создайте идеальную конфигурацию:</p>
                <ul class="features-list">
                    <li>Подбор совместимых комплектующих</li>
                    <li>Индивидуальная конфигурация</li>
                    <li>Доставка по всей России</li>
                </ul>
                <a href="order_form.php" class="btn">Начать сборку</a>
            </section>

            <section class="card">
                <h2>🌟 Наши преимущества</h2>
                <ul class="features-list">
                    <li>Гарантия 3 года</li>
                    <li>Бесплатная доставка</li>
                    <li>Тестирование под нагрузкой</li>
                    <li>Профессиональный кабель-менеджмент</li>
                </ul>
            </section>
        </div>

        <section class="card" style="margin-bottom: 2rem;">
            <h2>📸 Примеры наших работ</h2>
            <div class="horizontal-scroll">
                <div class="horizontal-scroll__content">
                    <img src="images/pc_build1.jpg" alt="Сборка 1" />
                    <img src="images/pc_build2.jpg" alt="Сборка 2" />
                    <img src="images/pc_build3.jpg" alt="Сборка 3" />
                    <img src="images/pc_build4.jpg" alt="Сборка 4" />
                    <img src="images/pc_build5.jpg" alt="Сборка 5" />
                </div>
            </div>
        </section>
    </main>

    <footer>
        © <?= date('Y') ?> PC Club | Все права защищены
    </footer>
</body
