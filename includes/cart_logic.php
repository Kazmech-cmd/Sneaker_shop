<?php
// Проверяем, запущена ли сессия, прежде чем с ней работать
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Инициализация корзины
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 2. Логика ДОБАВЛЕНИЯ в корзину
// Убираем проверку ['add_to_cart'], оставляем проверку ['product_id']
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $id = (int) $_POST['product_id'];

    // Проверь, что в product.php у тебя name="size", а не product_size
    $size = $_POST['size'] ?? 'Не выбран';
    $cart_key = $id . '_' . $size;

    // Собираем данные. Лучше брать их из скрытых полей формы, если не хочешь лишний раз лезть в БД
    $_SESSION['cart'][$cart_key] = [
        'id' => $id,
        'name' => $_POST['product_name'] ?? 'Товар',
        'price' => (float) ($_POST['product_price'] ?? 0),
        'image' => $_POST['product_image'] ?? '',
        'size' => $size,
        'quantity' => 1
    ];

    header("Location: ../index.php?page=cart"); // Путь назад в корень
    exit;
}

// 3. Логика УДАЛЕНИЯ из корзины
if (isset($_GET['remove_item'])) {
    $remove_key = $_GET['remove_item'];

    if (isset($_SESSION['cart'][$remove_key])) {
        unset($_SESSION['cart'][$remove_key]);
    }

    header("Location: index.php?page=cart");
    exit;
}