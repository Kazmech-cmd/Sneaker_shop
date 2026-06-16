<?php
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'];
}
?>

<main class="container" style="max-width: 600px; margin: 40px auto;">
    <h1 class="section-title">Оформление заказа</h1>
    
    <div class="checkout-form-container" style="background: #fff; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
        <p style="margin-bottom: 20px; color: #666;">
            Сумма к оплате: <strong><?= number_format($total_price, 0, '.', ' ') ?> ₽</strong>
        </p>

        <form action="includes/send_order.php" method="POST">
            <label style="display: block; margin-bottom: 8px;">Ваше имя</label>
            <input type="text" name="user_name" required placeholder="Иван Иванов" 
                   style="width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 10px;">

            <label style="display: block; margin-bottom: 8px;">Телефон для связи в Telegram</label>
            <input type="text" name="user_phone" required placeholder="+7 (999) 000-00-00" 
                   style="width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 10px;">

            <div style="background: #f0f7ff; padding: 15px; border-radius: 10px; margin-bottom: 25px; font-size: 0.9rem; color: #004085;">
                После оформления наш агент свяжется с вами для уточнения деталей доставки и оплаты.
            </div>

            <button type="submit" class="btn-main" style="width: 100%; padding: 15px;">
                Подтвердить заказ
            </button>
        </form>
    </div>
</main>