-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 01 2025 г., 14:30
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `web_magazin`
--

-- --------------------------------------------------------

--
-- Структура таблицы `assembly`
--

CREATE TABLE `assembly` (
  `assembly_order_id` int(13) NOT NULL,
  `assembly_price` int(7) NOT NULL,
  `date_of_admission` date NOT NULL,
  `date_of_delivery` date NOT NULL,
  `delivery_address` varchar(110) NOT NULL,
  `ctr_id` int(13) NOT NULL,
  `mtr_id` int(13) NOT NULL,
  `mbd_id` int(13) NOT NULL,
  `cpu_id` int(13) NOT NULL,
  `ram_id` int(13) NOT NULL,
  `cse_id` int(13) NOT NULL,
  `gpu_id` int(13) NOT NULL,
  `psu_id` int(13) NOT NULL,
  `sdu_id` int(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `assembly`
--

INSERT INTO `assembly` (`assembly_order_id`, `assembly_price`, `date_of_admission`, `date_of_delivery`, `delivery_address`, `ctr_id`, `mtr_id`, `mbd_id`, `cpu_id`, `ram_id`, `cse_id`, `gpu_id`, `psu_id`, `sdu_id`) VALUES
(1, 2000, '2025-04-20', '2025-04-02', 'Дом', 4, 2, 1, 1, 1, 1, 1, 1, 2),
(17, 20000, '2025-04-16', '2025-04-30', 'Дом', 1, 2, 9, 8, 8, 7, 7, 8, 8);

-- --------------------------------------------------------

--
-- Структура таблицы `customer`
--

CREATE TABLE `customer` (
  `ctr_id` int(13) NOT NULL,
  `full_name` varchar(110) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `legal_address` varchar(110) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `customer`
--

INSERT INTO `customer` (`ctr_id`, `full_name`, `phone_number`, `legal_address`) VALUES
(1, 'Иванов Иван', '+79998884422', 'Дом'),
(2, 'Сергей Сергеев', '+79998883311', 'Дом'),
(3, 'Павел Павлов', '+79993331231', 'Дом'),
(4, 'Владимир Владимирович', '+79992213321', 'Дом'),
(5, 'Денис Денисов', '+79228184223', 'Дом');

-- --------------------------------------------------------

--
-- Структура таблицы `gpu`
--

CREATE TABLE `gpu` (
  `gpu_id` int(13) NOT NULL,
  `gpu_name` varchar(110) NOT NULL,
  `gmemory_size` varchar(20) NOT NULL,
  `gpu_series` varchar(30) NOT NULL,
  `gpu_manufacturer` varchar(110) NOT NULL,
  `price` int(6) NOT NULL,
  `stock` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `gpu`
--

INSERT INTO `gpu` (`gpu_id`, `gpu_name`, `gmemory_size`, `gpu_series`, `gpu_manufacturer`, `price`, `stock`) VALUES
(1, 'Видеокарта Palit GeForce RTX 5080 GameRock', '16', 'GeForce RTX 5080', 'Китай [NE75080019T2-GB2030G]', 154999, 9),
(2, 'Видеокарта Palit GeForce RTX 5070 Ti GamingPro', '16', 'Palit GeForce RTX 5070 Ti Gami', 'Китай [NE7507T019T2-GB2031A]', 96999, 7),
(3, 'Видеокарта MSI GeForce RTX 4070 SUPER VENTUS 2X OC', '12', 'GeForce RTX 4070 SUPER', 'Тайвань (Китай)', 69999, 12),
(4, 'Видеокарта MSI GeForce RTX 4060 Ti VENTUS 2X BLACK', '16', 'GeForce RTX 4060 Ti', 'Китай', 49999, 17),
(5, 'Видеокарта GIGABYTE GeForce RTX 3090 TI', '24', 'GeForce RTX 3090 Ti', 'Китай', 98270, 2),
(6, 'Видеокарта Palit GeForce RTX 3060 Dual (LHR)', '12', 'GeForce RTX 3060', 'Китай', 30999, 40),
(7, 'Видеокарта KFA2 GeForce RTX 4060 CORE Black', '8', 'GeForce RTX 4060', 'Китай  [46NSL8MD9NXK]', 31999, 12),
(8, 'Видеокарта KFA2 GeForce GTX 1650 X Black', '4', 'GeForce GTX 1650', ' Китай [65SQL8DS93EK]', 15999, 50),
(9, 'Видеокарта KFA2 GeForce GT 1030', '2', 'GeForce GT 1030', 'Китай [30NPH4HVQ4SK]', 9299, 5),
(10, 'Видеокарта GIGABYTE GeForce GT 1030 OC', '2', 'GeForce GT 1030', 'Китай [GV-N1030OC-2GI]', 9499, 13);

-- --------------------------------------------------------

--
-- Структура таблицы `master`
--

CREATE TABLE `master` (
  `mtr_id` int(13) NOT NULL,
  `full_name` varchar(110) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `legal_address` varchar(110) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `master`
--

INSERT INTO `master` (`mtr_id`, `full_name`, `phone_number`, `legal_address`) VALUES
(1, 'Андрей Мегамастер', '+79113322213', 'Дом 2'),
(2, 'Денис Мастер', '+79993331231', 'Дом 2'),
(3, 'Кирилл Мастер ', '+79228184223', 'Дом 2'),
(4, 'Фёдор Мастер', '+79228184223', 'Дом 2'),
(5, 'Григорий Мастер', '+79992213321', 'Дом 2');

-- --------------------------------------------------------

--
-- Структура таблицы `mcase`
--

CREATE TABLE `mcase` (
  `cse_id` int(13) NOT NULL,
  `case_name` varchar(110) NOT NULL,
  `form_factor` varchar(20) NOT NULL,
  `case_size` varchar(30) NOT NULL,
  `case_manufacturer` varchar(110) NOT NULL,
  `price` int(6) NOT NULL,
  `stock` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `mcase`
--

INSERT INTO `mcase` (`cse_id`, `case_name`, `form_factor`, `case_size`, `case_manufacturer`, `price`, `stock`) VALUES
(1, 'Корпус Cougar MX600 RGB', 'Full-Tower', '523x324x524', ' Китай [3857C90.0017]', 10199, 40),
(2, 'Корпус LIAN LI O11 Dynamic EVO XL', 'Full-Tower', '522x304x531', ' Китай [G99.O11DEXL-X.R0]', 24199, 19),
(3, 'Корпус LIAN LI O11 Dynamic EVO XL белый', 'Full-Tower', '522х304х531', 'Китай  [G99.O11DEXL-W.R0]', 24199, 59),
(4, 'Корпус Cougar Panzer EVO RGB', 'Full-Tower', '556х266х616', 'Китай', 20999, 40),
(5, 'Корпус ZALMAN N4 Rev.1', 'Mid-Tower', '396х204х446', ' Китай', 4999, 16),
(6, 'Корпус Cougar FV150 RGB', 'Mid-Tower', '415х300х400', ' Китай  [FV150 RGB black]', 7999, 40),
(7, 'Корпус ARDOR GAMING Rare M6', 'Mid-Tower', '415х215х482', ' Китай', 6499, 69),
(8, 'Корпус ARDOR GAMING Rare Minicase MS1', 'Mini-Tower', '430х210х430', ' Китай', 5099, 120),
(9, 'Корпус Thermaltake Versa H16 TG', 'Mini-Tower', '355х216х385', ' Китай [CA-1Y8-00S1WN-00]', 3899, 79),
(10, 'Корпус ExeGate MI-301U', 'Desktop', '290х95х295', ' Китай [EX291267RUS]', 1950, 11);

-- --------------------------------------------------------

--
-- Структура таблицы `motherboard`
--

CREATE TABLE `motherboard` (
  `mbd_id` int(13) NOT NULL,
  `motherboard_name` varchar(110) NOT NULL,
  `form_factor` varchar(20) NOT NULL,
  `chipset` varchar(30) NOT NULL,
  `socket` varchar(30) NOT NULL,
  `board_manufacturer` varchar(110) NOT NULL,
  `price` int(6) NOT NULL,
  `stock` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `motherboard`
--

INSERT INTO `motherboard` (`mbd_id`, `motherboard_name`, `form_factor`, `chipset`, `socket`, `board_manufacturer`, `price`, `stock`) VALUES
(1, 'Материнская плата MSI B650 GAMING', 'Standard-ATX', 'AMD B650', 'AM5', 'Китай', 17999, 53),
(2, 'Материнская плата MSI MPG B550 GAMING', 'Standard-ATX', 'AMD B550', 'AM4', 'Китай', 10699, 50),
(3, 'Материнская плата MSI B760 GAMING', 'Standard-ATX', 'Intel B760', 'LGA 1700', 'Китай', 13299, 30),
(4, 'Материнская плата GIGABYTE Z890 GAMING X', 'Standard-ATX', 'Intel Z890', 'LGA 1851', 'Китай', 26499, 30),
(5, 'Материнская плата MSI PRO H610M-E DDR4', 'Micro-ATX', 'Intel H610', 'LGA 1700', 'Китай', 5399, 30),
(6, 'Материнская плата GIGABYTE B550M AORUS ELITE', 'Micro-ATX', 'AMD B550', 'AM4', 'Китай', 10499, 10),
(7, 'Материнская плата MSI PRO B650M-P', 'Micro-ATX', 'AMD B650', 'AM5', 'Китай', 10999, 19),
(8, 'Материнская плата ASRock 760GM-HDV', 'Micro-ATX', 'AMD 760G', 'AM3+', 'Китай', 2599, 84),
(9, 'Материнская плата MSI PRO H510M-B II', 'Micro-ATX', 'Intel H470', 'LGA 1200', 'Китай', 3999, 28),
(10, 'Материнская плата MSI PRO H510M-B', 'Micro-ATX', 'Intel H470', 'LGA 1200', 'Micro-ATX', 4699, 50);

-- --------------------------------------------------------

--
-- Структура таблицы `powerunit`
--

CREATE TABLE `powerunit` (
  `psu_id` int(13) NOT NULL,
  `power_name` varchar(110) NOT NULL,
  `capability` int(20) NOT NULL,
  `power_manufacturer` varchar(110) NOT NULL,
  `price` int(6) NOT NULL,
  `stock` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `powerunit`
--

INSERT INTO `powerunit` (`psu_id`, `power_name`, `capability`, `power_manufacturer`, `price`, `stock`) VALUES
(1, 'Блок питания DEEPCOOL PF750', 750, 'Китай [R-PF750D-HA0B-EU]', 4999, 49),
(2, 'Блок питания Cougar GEX850', 850, 'Китай [31GE085001P01]', 10199, 100),
(3, 'Блок питания DEEPCOOL PF600', 600, 'Китай [R-PF600D-HA0B-EU]', 3799, 42),
(4, 'Блок питания DEEPCOOL PF650', 650, 'Китай [R-PF650D-HA0B-EU]', 3999, 53),
(5, 'Блок питания Cougar GEC 750', 750, 'Китай [31GC075.0001P]', 7999, 75),
(6, 'Блок питания Cougar VTE 600W V2', 600, 'Китай [CGR BS-600]', 4199, 57),
(7, 'Блок питания Cougar STC 600', 600, 'Китай [CGR SC-600]', 3999, 86),
(8, 'Блок питания MSI MPG A850G PCIE5', 850, 'Китай [306-7ZP7B11-CE0]', 17999, 30),
(9, 'Блок питания DEEPCOOL PF450', 450, 'Китай [R-PF450D-HA0B-EU]', 2999, 50),
(10, 'Блок питания Cougar GEX X2 1000 ', 1000, 'Китай [GEX X2 1000]', 12999, 12);

-- --------------------------------------------------------

--
-- Структура таблицы `processor`
--

CREATE TABLE `processor` (
  `cpu_id` int(13) NOT NULL,
  `unit_name` varchar(110) NOT NULL,
  `socket` varchar(30) NOT NULL,
  `base_frequency` int(20) NOT NULL,
  `number_of_cores` int(10) NOT NULL,
  `cpu_manufacturer` varchar(110) NOT NULL,
  `price` int(6) NOT NULL,
  `stock` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `processor`
--

INSERT INTO `processor` (`cpu_id`, `unit_name`, `socket`, `base_frequency`, `number_of_cores`, `cpu_manufacturer`, `price`, `stock`) VALUES
(1, 'Процессор AMD Ryzen 7 7800X3D', 'AM5', 3900, 8, ' Вьетнам [100-000000910]', 39999, 49),
(2, 'Процессор AMD Ryzen 5 7500F', 'AM5', 3500, 6, 'Малайзия [100-000000597]', 14299, 120),
(3, 'Процессор AMD Ryzen 7 5700X3D', 'AM4', 3500, 6, 'Малайзия [100-000001503]', 23499, 60),
(4, ' Процессор AMD Ryzen 7 5700X', 'AM4', 3300, 8, 'Вьетнам [100-000000926]', 11799, 200),
(5, 'Процессор AMD FX-4300', 'AM3+', 3900, 4, ' Малайзия  [FD4300WMW4MHK]', 2150, 19),
(6, 'Процессор Intel Core Ultra 9 285K', 'LGA 1851', 3750, 8, 'Вьетнам  [AT8076806419]', 63299, 30),
(7, 'Процессор Intel Core Ultra 7 265KF', 'LGA 1851', 3900, 8, 'Малайзия  [AT8076806410]', 39299, 40),
(8, 'Процессор Intel Core i5-12400F', 'LGA 1700', 2500, 6, ' Малайзия [CM8071504650609-SRL5Z]', 9999, 57),
(9, 'Процессор Intel Core i3-12100', 'LGA 1700', 3300, 4, 'Малайзия  [CM8071504651012-SRL62]', 9199, 12),
(10, 'Процессор Intel Core i5-11400F', 'LGA 1200', 2600, 6, 'Вьетнам  [CM8070804497016-SRKP1]', 7999, 40);

-- --------------------------------------------------------

--
-- Структура таблицы `ram`
--

CREATE TABLE `ram` (
  `ram_id` int(13) NOT NULL,
  `ram_name` varchar(110) NOT NULL,
  `memory_size` int(30) NOT NULL,
  `type` varchar(10) NOT NULL,
  `base_frequency` int(20) NOT NULL,
  `ram_manufacturer` varchar(110) NOT NULL,
  `price` int(6) NOT NULL,
  `stock` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `ram`
--

INSERT INTO `ram` (`ram_id`, `ram_name`, `memory_size`, `type`, `base_frequency`, `ram_manufacturer`, `price`, `stock`) VALUES
(1, 'Оперативная память PatriotSignaturePremium (32гб)', 32, 'DDR5', 5600, 'Тайвань (Китай) [PSP532G5600KH1]', 6799, 200),
(2, 'Оперативная память Patriot Viper 3 (16гб)', 16, 'DDR5', 1600, 'Тайвань (Китай) [PV316G160C9K]', 2550, 160),
(3, 'Оперативная память Patriot Signature Line (16гб)', 16, 'DDR5', 3200, 'Тайвань (Китай) [PSD416G3200K]', 2599, 60),
(4, 'Оперативная память Patriot Viper Elite II (32гб)', 32, 'DDR5', 3200, 'Тайвань (Китай) [PVE2432G320C8K]', 5299, 70),
(5, 'Оперативная память Patriot Viper Venom (64гб)', 64, 'DDR5', 5200, ' Тайвань (Китай) [PVV564G520C40K]', 16599, 93),
(6, 'Оперативная память Patriot Signature Line (d416гб)', 16, 'DDR4', 3200, 'Тайвань (Китай) [PSD416G3200K]', 2599, 180),
(7, 'Оперативная память Patriot Viper Elite II (d432гб)', 32, 'DDR4', 3200, 'Тайвань (Китай) [PVE2432G320C8K]', 5299, 42),
(8, 'Оперативная память Patriot Viper Steel (d432гб)', 32, 'DDR4', 3200, 'Тайвань (Китай) [PVS416G320C6K]', 3099, 39),
(9, 'Оперативная память Patriot Viper 3 (d316гб)', 16, 'DDR3', 1600, 'Тайвань (Китай) [PV316G160C9K]', 2550, 102),
(10, 'Оперативная память Patriot Signature (d38гб)', 8, 'DDR3', 1600, 'Тайвань (Китай) [PSD38G16002]', 999, 13);

-- --------------------------------------------------------

--
-- Структура таблицы `storage`
--

CREATE TABLE `storage` (
  `sdu_id` int(13) NOT NULL,
  `storage_name` varchar(110) NOT NULL,
  `storage_capacity` int(20) NOT NULL,
  `reading_speed` int(20) NOT NULL,
  `sdu_type` varchar(20) NOT NULL,
  `sdu_manufacturer` varchar(110) NOT NULL,
  `price` int(6) NOT NULL,
  `stock` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `storage`
--

INSERT INTO `storage` (`sdu_id`, `storage_name`, `storage_capacity`, `reading_speed`, `sdu_type`, `sdu_manufacturer`, `price`, `stock`) VALUES
(1, 'Жесткий диск WD Blue (1тб)', 1024, 750, 'HDD', 'Таиланд [WD10EZEX]', 4999, 200),
(2, 'Жесткий диск WD Red Plus (2тб)', 2048, 750, 'HDD', 'Таиланд [WD20EFPX]', 7799, 320),
(3, 'Жесткий диск WD Red Plus (4тб)', 4096, 750, 'HDD', 'Таиланд [WD40EFPX]', 10999, 124),
(4, 'Жесткий диск WD Red Plus (6тб)', 6144, 750, 'HDD', 'Таиланд [WD60EFPX]', 16999, 70),
(5, 'Жесткий диск Seagate Exos X18 (16тб)', 16384, 750, 'HDD', 'Таиланд [ST16000NM000J]', 29499, 40),
(6, 'SATA накопитель Samsung 870 EVO (0.5sтб)', 500, 530, 'SSD', 'Корея, Республика [MZ-77E500BW]', 4999, 400),
(7, 'SATA накопитель Samsung 870 EVO (1sтб)', 1000, 530, 'SSD', 'Корея, Республика [MZ-77E1T0BW]', 8499, 300),
(8, 'SATA накопитель Samsung 870 EVO (2sтб)', 2000, 560, 'SSD', 'Китай [MZ-77E2T0BW]', 16999, 39),
(9, 'SATA накопитель Samsung 870 EVO (4sтб)', 4000, 560, 'SSD', 'Китай [MZ-77E4T0BW]', 29999, 90),
(10, 'SATA накопитель Samsung 870 QVO (8sтб)', 8000, 560, 'SSD', 'Корея, Республика [MZ-77Q8T0BW]', 63999, 19);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `adminflag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `adminflag`) VALUES
(2, 'admin', '$2y$10$qRIhlMs72PNZFLUwsY3Ei.MRIjOVC6gPhxH48QK9Jf7WXnUBLdOoK', 1),
(3, 'user', '$2y$10$qL7GvyUnTy55ZjoiNY1tl.NWoh9dUGm1v4ii2burz2qrN9gGqOm5y', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `assembly`
--
ALTER TABLE `assembly`
  ADD PRIMARY KEY (`assembly_order_id`),
  ADD KEY `mtr_id` (`mtr_id`),
  ADD KEY `ctr_id` (`ctr_id`),
  ADD KEY `cpu_id` (`cpu_id`),
  ADD KEY `cse_id` (`cse_id`),
  ADD KEY `gpu_id` (`gpu_id`),
  ADD KEY `mbd_id` (`mbd_id`),
  ADD KEY `psu_id` (`psu_id`),
  ADD KEY `sdu_id` (`sdu_id`),
  ADD KEY `ram_id` (`ram_id`);

--
-- Индексы таблицы `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`ctr_id`);

--
-- Индексы таблицы `gpu`
--
ALTER TABLE `gpu`
  ADD PRIMARY KEY (`gpu_id`),
  ADD KEY `price` (`price`);

--
-- Индексы таблицы `master`
--
ALTER TABLE `master`
  ADD PRIMARY KEY (`mtr_id`);

--
-- Индексы таблицы `mcase`
--
ALTER TABLE `mcase`
  ADD PRIMARY KEY (`cse_id`);

--
-- Индексы таблицы `motherboard`
--
ALTER TABLE `motherboard`
  ADD PRIMARY KEY (`mbd_id`);

--
-- Индексы таблицы `powerunit`
--
ALTER TABLE `powerunit`
  ADD PRIMARY KEY (`psu_id`);

--
-- Индексы таблицы `processor`
--
ALTER TABLE `processor`
  ADD PRIMARY KEY (`cpu_id`);

--
-- Индексы таблицы `ram`
--
ALTER TABLE `ram`
  ADD PRIMARY KEY (`ram_id`);

--
-- Индексы таблицы `storage`
--
ALTER TABLE `storage`
  ADD PRIMARY KEY (`sdu_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `assembly`
--
ALTER TABLE `assembly`
  MODIFY `assembly_order_id` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `customer`
--
ALTER TABLE `customer`
  MODIFY `ctr_id` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `gpu`
--
ALTER TABLE `gpu`
  MODIFY `gpu_id` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `master`
--
ALTER TABLE `master`
  MODIFY `mtr_id` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `mcase`
--
ALTER TABLE `mcase`
  MODIFY `cse_id` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `motherboard`
--
ALTER TABLE `motherboard`
  MODIFY `mbd_id` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `powerunit`
--
ALTER TABLE `powerunit`
  MODIFY `psu_id` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `processor`
--
ALTER TABLE `processor`
  MODIFY `cpu_id` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `ram`
--
ALTER TABLE `ram`
  MODIFY `ram_id` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `storage`
--
ALTER TABLE `storage`
  MODIFY `sdu_id` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `assembly`
--
ALTER TABLE `assembly`
  ADD CONSTRAINT `assembly_ibfk_1` FOREIGN KEY (`ctr_id`) REFERENCES `customer` (`ctr_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assembly_ibfk_2` FOREIGN KEY (`mtr_id`) REFERENCES `master` (`mtr_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assembly_ibfk_3` FOREIGN KEY (`cpu_id`) REFERENCES `processor` (`cpu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assembly_ibfk_4` FOREIGN KEY (`cse_id`) REFERENCES `mcase` (`cse_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assembly_ibfk_5` FOREIGN KEY (`gpu_id`) REFERENCES `gpu` (`gpu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assembly_ibfk_6` FOREIGN KEY (`mbd_id`) REFERENCES `motherboard` (`mbd_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assembly_ibfk_7` FOREIGN KEY (`psu_id`) REFERENCES `powerunit` (`psu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assembly_ibfk_8` FOREIGN KEY (`sdu_id`) REFERENCES `storage` (`sdu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assembly_ibfk_9` FOREIGN KEY (`ram_id`) REFERENCES `ram` (`ram_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
