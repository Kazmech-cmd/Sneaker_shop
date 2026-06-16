<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['cart'])) {
    $name = htmlspecialchars($_POST['user_name']);
    $phone = htmlspecialchars($_POST['user_phone']);
    
    $items_text = "";
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $items_text .= "👟 " . $item['name'] . " (Разм: " . $item['size'] . ") — " . number_format($item['price'], 0, '.', ' ') . " ₽\n";
        $total += $item['price'];
    }

    $message = "🔔 НОВЫЙ ЗАКАЗ!\n\n👤 Клиент: $name\n📞 Телефон: $phone\n\n📦 Состав:\n$items_text\n💰 Итого: " . number_format($total, 0, '.', ' ') . " ₽";

    $botToken = "";
    $chatId = "";
    
    // Формируем ссылку для JS
    $tg_url = "https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=" . urlencode($message);

    // Очищаем корзину заранее
    unset($_SESSION['cart']);

    // Вместо PHP-запроса выводим JS, который сделает запрос из браузера (где работает VPN)
    echo "
    <script>
        fetch('$tg_url', { mode: 'no-cors' })
        .then(() => {
            window.location.href = '../index.php?page=thanks';
        })
        .catch(() => {
            window.location.href = '../index.php?page=thanks';
        });
    </script>
    ";
    exit();
}