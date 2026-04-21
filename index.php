<?php
$jsonString = file_get_contents('ProgramWebsite.jsn');
if ($jsonString === false) {
    die('Файл не найден');
}

$data = json_decode($jsonString, true);

if (isset($data[0]) && is_array($data[0])) {
    $programs = $data;
} elseif (isset($data['programs'])) {
    $programs = $data['programs'];
} elseif (isset($data['data'])) {
    $programs = $data['data'];
} else {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die('Структура');
}

//вывод 12 карточками (4 ряда по 3)
$programs = array_slice($programs, 0, 12);
?>
<link rel="stylesheet" href="style.css">
    <div class="container">
        <h1>Каталог образовательных программ</h1>
        <p class="total">Всего программ: <?php echo count($programs); ?></p>
        
        <div class="catalog">
            <?php foreach ($programs as $program): ?>
                <div class="card">
                    <img src="test1.jpg" alt="Изображение программы" class="card-image">
                    <h3 class="card-title"><?php echo $program['name'] ?? 'Без названия'; ?></h3>
                    <p class="card-hours"><?php echo $program['hours'] ?? '0'; ?> ч.</p>
                    <p class="card-price">
                        <?php 
                        $price = $program['price'] ?? 0;
                        if ($price > 0) {
                            echo number_format($price, 0, ',', ' ') . ' ₽';
                        } else {
                            echo 'Бесплатно';
                        }
                        ?>
                    </p>
                    <a href="index1.php" class="card-btn">Подробнее →</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>