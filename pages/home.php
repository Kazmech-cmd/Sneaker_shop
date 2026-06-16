<?php
require_once 'includes/db.php'; // Подключаем базу

// Получаем все товары из базы
$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll();
?>

<main>
    <section class="hero" style="padding: 50px 5%; background: #f4f4f9;">
        <h1>Добро пожаловать в <span style="color: #9999c1;">SNEAKERS</span></h1>
    </section>

    <section class="container" style="padding: 40px 5%;">
        <h2 class="section-title">Наши товары</h2>
        <div class="product-grid" 
             style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">

            <?php
            try {
                if ($products) {
                    foreach ($products as $product) {
                        echo '<div class="product-card" style="background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #eee; text-align: center; display: flex; flex-direction: column; justify-content: space-between;">';
                        
                        // Добавляем вывод картинки
                        echo '<div style="margin-bottom: 15px; height: 160px; display: flex; align-items: center; justify-content: center;">';
                        echo '<img src="assets/img/' . htmlspecialchars($product['image_url']) . '" alt="' . htmlspecialchars($product['model_name']) . '" style="max-width: 100%; max-height: 100%; object-fit: contain;">';
                        echo '</div>';

                        echo '<h3>' . htmlspecialchars($product['model_name']) . '</h3>';
                        echo '<p style="color: #9999c1; font-weight: bold; margin: 10px 0;">' . number_format($product['price'], 0, '.', ' ') . ' ₽</p>';
                        
                        // Ссылка на страницу товара вместо просто кнопки
                        echo '<a href="index.php?page=product&id=' . $product['id'] . '" class="btn-main" style="background:#9999c1; color:#fff; text-decoration:none; padding:10px; width:100%; border-radius:5px; display: inline-block;">Купить</a>';
                        
                        echo '</div>';
                    }
                } else {
                    echo "<p>В базе данных пока нет товаров. Добавьте их через админку!</p>";
                }
            } catch (Exception $e) {
                echo "<p style='color: red;'>Ошибка базы данных: " . $e->getMessage() . "</p>";
            }
            ?>

        </div>
    </section>
</main>