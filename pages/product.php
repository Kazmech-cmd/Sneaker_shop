<?php
require_once 'includes/db.php';

// 1. Получаем ID товара из URL
$product_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// 2. Запрос к БД
$stmt = $pdo->prepare("
    SELECT p.*, b.name as brand_name 
    FROM products p 
    JOIN brands b ON p.brand_id = b.id 
    WHERE p.id = ?
");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<div class='container' style='text-align:center; padding: 100px 0;'>
            <h1>Товар не найден</h1>
            <a href='index.php?page=catalog' class='btn-main'>Вернуться в каталог</a>
          </div>";
    return;
}

$sizes = explode(',', $product['available_sizes']);
?>

<link rel="stylesheet" href="/sneaker_shop/assets/css/style.css">

<main class="container">
    <div class="product-detail">

        <div class="product-image-large">
            <div class="img-inner">
                <img src="assets/img/<?= $product['image_url'] ?>"
                    alt="<?= htmlspecialchars($product['model_name']) ?>">
            </div>
        </div>

        <div class="product-info">
            <span class="brand-label"><?= htmlspecialchars($product['brand_name'] ?? 'Nike') ?></span>
            <h1 class="product-title"><?= htmlspecialchars($product['model_name']) ?></h1>
            <p class="article">Артикул: <?= htmlspecialchars($product['article'] ?? '000001') ?></p>
            <p class="price-big"><?= number_format($product['price'], 0, '.', ' ') ?> ₽</p>

            <form action="/sneaker_shop/includes/cart_logic.php" method="POST">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['model_name']) ?>">
                <input type="hidden" name="product_price" value="<?= $product['price'] ?>">
                <input type="hidden" name="product_image" value="<?= $product['image_url'] ?>">

                <h3 class="size-heading">Выберите российский размер:</h3>

                <div class="size-selector">
                    <?php
                    // Пример размеров, в реальности можно брать из БД
                    $sizes = [38, 39, 40, 41, 42, 43, 44, 45];
                    foreach ($sizes as $s):
                        ?>
                        <label class="size-item">
                            <input type="radio" name="size" value="<?= $s ?>" class="size-input" required>
                            <span class="size-box"><?= $s ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="btn-main-large">Добавить в корзину</button>
            </form>
        </div>

    </div>
</main>