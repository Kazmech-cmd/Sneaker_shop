<?php
// Проверка где запущен сайт
if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
    // локалка
    $host = 'localhost';
    $db   = 'sneaker_shop';
    $user = 'root';
    $pass = '';
} else {
    // хостинг
    $host = 'sql208.infinityfree.com';
    $db   = 'if0_41017050_sneaker_shop';
    $user = 'if0_41017050';
    $pass = 'jK1uZRmpIS'; 
}

$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("Ошибка подключения к базе данных. Проверьте настройки.");
}
?>