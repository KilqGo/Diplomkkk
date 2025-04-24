<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сборка ПК - Главная страница</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Сборка Персональных Компьютеров</h1>
        <nav>
            <ul>
                <li><a href="edit_content.php">Редактировать содержание</a></li>
                <li><a href="order_form.php">Оформить заказ</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="intro">
            <h2>Добро пожаловать!</h2>
            <p>Мы предлагаем услуги по сборке персональных компьютеров под ваши нужды. Выберите компоненты, и мы соберем для вас идеальный ПК!</p>
        </section>

        <section class="features">
            <h2>Почему выбирают нас?</h2>
            <ul>
                <li>Индивидуальный подход к каждому клиенту</li>
                <li>Широкий выбор комплектующих</li>
                <li>Гарантия качества на все сборки</li>
                <li>Быстрая и надежная доставка</li>
            </ul>
        </section>

        <section class="cta">
            <h2>Начните собирать свой ПК прямо сейчас!</h2>
            <p><a href="order_form.php" class="btn">Оформить заказ</a></p>
        </section>

        <section class="gallery">
            <h2>Наши работы</h2>
            <div class="image-grid">
                <img src="images/pc_build1.jpg" alt="Собранный ПК 1">
                <img src="images/pc_build2.jpg" alt="Собранный ПК 2">
                <img src="images/pc_build3.jpg" alt="Собранный ПК 3">
                <img src="images/pc_build4.jpg" alt="Собранный ПК 4">
                <img src="images/pc_build5.jpg" alt="Собранный ПК 5">
            </div>
        </section>

    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> PC Club</p> 
    </footer>
</body>
</html>