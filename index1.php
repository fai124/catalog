<?php
// Получаю ID программы из URL
$programId = $_GET['id'] ?? '';

if (empty($programId)) {
    die('ID программы не указан');
}

$jsonString = file_get_contents('ProgramWebsite.jsn');
if ($jsonString === false) {
    die('Файл с данными не найден');
}

$allPrograms = json_decode($jsonString, true);

// Ищю программу по ID
$currentProgram = null;
foreach ($allPrograms as $program) {
    if ($program['id'] == $programId) {
        $currentProgram = $program;
        break;
    }
}

if (!$currentProgram) {
    die('Программа не найдена');
}

// Форматирование
function formatPrice($price) {
    if ($price > 0) {
        return number_format($price, 0, ',', ' ') . ' ₽';
    }
    return 'Бесплатно';
}
?>

    
    <link rel="stylesheet" href="styleIndex3.css">
<body>
    <div class="container">
        <div class="detail-container">
            <a href="index.php" class="back-link">Назад к каталогу</a>
            
            <h1 class="detail-title"><?php echo htmlspecialchars($currentProgram['name'] ?? 'Без названия'); ?></h1>
            
            <div class="detail-section">
                <span class="detail-label"> Специальность:</span>
                <span class="detail-value"><?php echo htmlspecialchars($currentProgram['specialty'] ?? '—'); ?></span>
            </div>
            
            <div class="detail-section">
                <span class="detail-label"> Академические часы:</span>
                <span class="detail-value"><?php echo $currentProgram['hours'] ?? '0'; ?> ч.</span>
            </div>
            
            <div class="detail-section">
                <span class="detail-label"> Стоимость:</span>
                <span class="detail-value price-large"><?php echo formatPrice($currentProgram['price'] ?? 0); ?></span>
            </div>
            
            <div class="detail-section">
                <span class="detail-label"> Кафедра:</span>
                <span class="detail-value"><?php echo htmlspecialchars($currentProgram['division'] ?? '—'); ?></span>
            </div>
            
            <div class="detail-section">
                <span class="detail-label"> Тип программы:</span>
                <span class="detail-value"><?php echo htmlspecialchars($currentProgram['programtype'] ?? '—'); ?></span>
            </div>
            
            
            <?php if (!empty($currentProgram['description'])): ?>
            <div class="detail-section">
                <span class="detail-label"> Описание:</span>
                <div class="detail-value" style="margin-top:10px;"><?php echo nl2br(htmlspecialchars($currentProgram['description'])); ?></div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($currentProgram['goals'])): ?>
            <div class="detail-section">
                <span class="detail-label"> Цель программы:</span>
                <div class="detail-value" style="margin-top:10px;"><?php echo nl2br(htmlspecialchars($currentProgram['goals'])); ?></div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($currentProgram['нyperlinkNMO'])): ?>
            <div class="detail-section">
                <span class="detail-label"> Ссылка НМО:</span>
                <a href="<?php echo htmlspecialchars($currentProgram['нyperlinkNMO']); ?>" target="_blank">Перейти →</a>
            </div>
            <?php endif; ?>
            
            <a href="index.php" class="btn-back">Записаться на курс</a>
        </div>
    </div>
</body>
