<?php
$jsonString = file_get_contents('ProgramWebsite.jsn');
if($jsonString === false) {
    die('файл не найден');
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
?>

<h1>Каталог образовательных программ</h1>
    
    <?php foreach ($programs as $program): ?>
        <div>
            <h3><?php echo $program['name'] ?? 'Без названия'; ?></h3>
            <img src="test1.jpg" alt="" style="height: 200px width:200px">
            <p><strong>Часы:</strong> <?php echo $program['hours'] ?? '0'; ?> ч.</p>
            <p><strong>Цена:</strong> <?php echo ($program['price'] ?? 0) > 0 ? number_format($program['price'], 0, ',', ' ') . ' ₽' : 'Бесплатно'; ?></p>
            <p><strong>Описание:</strong> <?php echo mb_substr($program['description'] ?? '', 0, 100); ?>...</p>
            <button>
                <a href="index1.php">Подробнее</a>
            </button>
        </div>
    <?php endforeach; ?>
    
    <p>Всего программ: <?php echo count($programs); ?></p>