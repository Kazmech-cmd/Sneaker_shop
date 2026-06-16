<?php
// includes/db.php уже в индексе, тут только расчет суммы
$total_price = 0; 
?>

<link rel="stylesheet" href="/sneaker_shop/assets/css/style.css">

<main class="container">
    <h1 class="section-title">Ваша корзина</h1>

    <?php if (empty($_SESSION['cart'])): ?>
        <div style="text-align: center; padding: 50px;">
            <p style="font-size: 1.2rem; color: #888;">В корзине пока ничего нет.</p>
            <a href="index.php?page=catalog" class="btn-main" style="margin-top: 20px;">Перейти к покупкам</a>
        </div>
    <?php else: ?>
        <div class="cart-layout">

            <div class="cart-items">
                <?php foreach ($_SESSION['cart'] as $key => $item): 
                    // Накапливаем итоговую сумму
                    $total_price += $item['price']; 
                ?>
                    <div class="cart-item">
                        <img src="assets/img/<?= htmlspecialchars($item['image']) ?>"
                            alt="<?= htmlspecialchars($item['name']) ?>">

                        <div class="cart-item-info">
                            <h3><?= htmlspecialchars($item['name']) ?></h3>
                            <p>Размер: <?= htmlspecialchars($item['size']) ?></p>
                        </div>

                        <div class="cart-item-price">
                            <span class="price"><?= number_format($item['price'], 0, '.', ' ') ?> ₽</span>
                            <a href="includes/cart_logic.php?remove_item=<?= $key ?>" class="btn-remove">Удалить</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h3 style="margin-bottom: 20px;">Итого</h3>
                <div class="summary-row">
                    <span>К оплате:</span>
                    <span style="color: var(--primary); font-weight: bold;">
                        <?= number_format($total_price, 0, '.', ' ') ?> ₽
                    </span>
                </div>
                <a href="index.php?page=checkout" class="btn-main"
                    style="display: block; text-align: center; text-decoration: none; margin-top: 20px;">
                    Оформить заказ
                </a>
            </div>
        </div>
    <?php endif; ?>
</main>