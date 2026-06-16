<?php
session_start();


// 1. Подключаем ядро (БД и логику корзины)
require_once 'includes/db.php';
require_once 'includes/cart_logic.php';

// 2. Логика роутинга
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// 3. Подключаем шапку сайта
if (file_exists('includes/header.php')) {
    include 'includes/header.php';
}

// 4. Подключаем контент текущей страницы
$content_file = "pages/$page.php";
if (file_exists($content_file)) {
    include $content_file;
} else {
    include "pages/home.php";
}

// 5. Подключаем подвал сайта
if (file_exists('includes/footer.php')) {
    include 'includes/footer.php';
}
?>