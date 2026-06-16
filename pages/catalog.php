<?php
require_once 'includes/db.php';

$where = [];
$params = [];

/* Бренды */
$selectedBrands = isset($_GET['brand']) ? (array) $_GET['brand'] : [];
if (!empty($selectedBrands)) {
    $placeholders = implode(',', array_fill(0, count($selectedBrands), '?'));
    $where[] = "brand_id IN ($placeholders)";
    $params = array_merge($params, $selectedBrands);
}

/* Пол */
$selectedGenders = isset($_GET['gender']) ? (array) $_GET['gender'] : [];
if (!empty($selectedGenders)) {
    $placeholders = implode(',', array_fill(0, count($selectedGenders), '?'));
    $where[] = "gender IN ($placeholders)";
    $params = array_merge($params, $selectedGenders);
}

/* ЦЕНА (Добавленный блок) */
$min_price = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (int)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (int)$_GET['max_price'] : 100000;

$where[] = "price >= ?";
$params[] = $min_price;
$where[] = "price <= ?";
$params[] = $max_price;

/* SQL */
$sql = "SELECT * FROM products";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>

<main class="container catalog-page-container">
    <h1 class="section-title">Каталог кроссовок</h1>

    <div class="catalog-layout">

        <aside class="filters-sidebar">
            <form action="index.php" method="GET">
                <input type="hidden" name="page" value="catalog">

                <div class="filter-group">
                    <h4>Бренды</h4>
                    <label class="filter-label">
                        <input type="checkbox" name="brand[]" value="1" <?= in_array(1, $selectedBrands) ? 'checked' : '' ?>>
                        Nike
                    </label>
                    <label class="filter-label">
                        <input type="checkbox" name="brand[]" value="2" <?= in_array(2, $selectedBrands) ? 'checked' : '' ?>>
                        Adidas
                    </label>
                    <label class="filter-label">
                        <input type="checkbox" name="brand[]" value="3" <?= in_array(3, $selectedBrands) ? 'checked' : '' ?>>
                        Jordan
                    </label>
                </div>

                <div class="filter-group">
                    <h4>Пол</h4>
                    <label class="filter-label">
                        <input type="checkbox" name="gender[]" value="Мужской" <?= in_array('Мужской', $selectedGenders) ? 'checked' : '' ?>>
                        Мужской
                    </label>
                    <label class="filter-label">
                        <input type="checkbox" name="gender[]" value="Женский" <?= in_array('Женский', $selectedGenders) ? 'checked' : '' ?>>
                        Женский
                    </label>
                    <label class="filter-label">
                        <input type="checkbox" name="gender[]" value="Унисекс" <?= in_array('Унисекс', $selectedGenders) ? 'checked' : '' ?>>
                        Унисекс
                    </label>
                </div>

                <div class="filter-group">
                    <h4>Стоимость (₽)</h4>
                    <div id="price-slider" style="margin: 20px 10px 30px;"></div>

                    <div class="price-inputs">
                        <input type="number" name="min_price" id="input-min" placeholder="от"
                            value="<?= $_GET['min_price'] ?? 0 ?>">
                        <input type="number" name="max_price" id="input-max" placeholder="до"
                            value="<?= $_GET['max_price'] ?? 100000 ?>">
                    </div>
                </div>

                <button type="submit" class="btn-main" style="width: 100%; margin-top: 10px;">Применить</button>
                <a href="index.php?page=catalog" class="btn-reset">Сбросить фильтры</a>
            </form>
        </aside>

        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="card-img-wrapper">
                        <img src="assets/img/<?= $product['image_url'] ?>"
                            alt="<?= htmlspecialchars($product['model_name']) ?>">
                    </div>
                    <h3><?= htmlspecialchars($product['model_name']) ?></h3>
                    <span class="price"><?= number_format($product['price'], 0, '.', ' ') ?> ₽</span>
                    <a href="index.php?page=product&id=<?= $product['id'] ?>" class="btn-main">Подробнее</a>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</main>