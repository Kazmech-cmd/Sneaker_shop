<?php
// admin/admin.php
require_once '../includes/db.php';

// 1. Логика УДАЛЕНИЯ товара
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    header("Location: admin.php");
    exit;
}

// 2. Логика ДОБАВЛЕНИЯ товара с загрузкой картинки
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['model_name'];
    $price = $_POST['price'];
    $brand = $_POST['brand_id'];
    $gender = $_POST['gender'];

    // Проверяем, передан ли файл и нет ли ошибок
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
        $fileName = $_FILES['product_image']['name'];
        $fileTmpName = $_FILES['product_image']['tmp_name'];

        // Путь для сохранения: выходим из admin/ и идем в assets/img/
        $uploadPath = '../assets/img/' . $fileName;

        // Перемещаем файл из временной папки в папку проекта
        if (move_uploaded_file($fileTmpName, $uploadPath)) {
            // Сохраняем в БД только имя файла
            $stmt = $pdo->prepare("INSERT INTO products (model_name, price, brand_id, gender, image_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $price, $brand, $gender, $fileName]);

            header("Location: admin.php?success=1");
            exit;
        } else {
            $error = "Ошибка при сохранении файла на сервере.";
        }
    } else {
        $error = "Пожалуйста, выберите изображение.";
    }
}

// 3. Получение списка всех товаров
$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Админ-панель | Sneaker Shop</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 30px;
            margin-top: 20px;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .admin-table th,
        .admin-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .admin-form {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            margin: 8px 0 18px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 0.9rem;
        }

        .status-msg {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .success {
            background: #e6ffed;
            color: #28a745;
            border: 1px solid #b7eb8f;
        }

        .error {
            background: var(--error-bg);
            color: var(--error);
            border: 1px solid #ffa39e;
        }
    </style>
</head>

<body>

    <header>
        <div class="logo">Sneaker Admin</div>
        <nav class="nav-links">
            <a href="../index.php">Вернуться на сайт</a>
        </nav>
    </header>

    <main class="container" style="max-width: 1400px;">
        <h1>Управление каталогом</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="status-msg success">✅ Товар успешно добавлен!</div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="status-msg error">❌ <?= $error ?></div>
        <?php endif; ?>

        <div class="admin-grid">
            <div class="admin-list">
                <table class="admin-table">
                    <thead style="background: var(--dark); color: white;">
                        <tr>
                            <th>Фото</th>
                            <th>Модель</th>
                            <th>Цена</th>
                            <th>Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td>
                                    <img src="../assets/img/<?= htmlspecialchars($p['image_url']) ?>" alt=""
                                        style="width: 70px; height: 50px; object-fit: contain; background: #f9f9f9; border-radius: 8px;">
                                </td>
                                <td><strong><?= htmlspecialchars($p['model_name']) ?></strong></td>
                                <td><?= number_format($p['price'], 0, '.', ' ') ?> ₽</td>
                                <td>
                                    <a href="?delete_id=<?= $p['id'] ?>"
                                        style="color: var(--error); text-decoration: underline;"
                                        onclick="return confirm('Удалить этот товар?')">Удалить</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="admin-form">
                <h3 style="margin-bottom: 20px; border-left: 4px solid var(--primary); padding-left: 15px;">Добавить
                    новинку</h3>
                <form method="POST" enctype="multipart/form-data"> <label>Название модели</label>
                    <input type="text" name="model_name" class="form-input" placeholder="Напр: Nike Air Force 1"
                        required>

                    <label>Цена (₽)</label>
                    <input type="number" name="price" class="form-input" placeholder="11900" required>

                    <label>Бренд</label>
                    <select name="brand_id" class="form-input">
                        <option value="1">Nike</option>
                        <option value="2">Adidas</option>
                        <option value="3">Jordan</option>
                        <option value="4">Puma</option>
                    </select>

                    <label>Категория</label>
                    <select name="gender" class="form-input">
                        <option value="Мужской">Мужской</option>
                        <option value="Женский">Женский</option>
                        <option value="Унисекс">Унисекс</option>
                    </select>

                    <label>Изображение (JPG/PNG)</label>
                    <input type="file" name="product_image" class="form-input" accept="image/*" required>

                    <button type="submit" name="add_product" class="btn-main"
                        style="width: 100%; padding: 15px; margin-top: 10px;">
                        Добавить в каталог
                    </button>
                </form>
            </div>
        </div>
    </main>

</body>

</html>