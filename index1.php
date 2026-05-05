<?php
$programId = $_GET['id'] ?? '';

if (empty($programId)) {
    die('ID программы не указан');
}

$jsonString = file_get_contents('ProgramWebsite.jsn');
if ($jsonString === false) {
    die('Файл с данными не найден');
}

$allPrograms = json_decode($jsonString, true);

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

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($currentProgram['name'] ?? 'Программа'); ?></title>
    <link rel="stylesheet" href="styleIndex13.css">
</head>
<body>
    <div class="container">
        <div class="detail-container">
            <a href="index.php" class="back-link">← Назад к каталогу</a>
            
            <h1 class="detail-title"><?php echo htmlspecialchars($currentProgram['name'] ?? 'Без названия'); ?></h1>
            
            <div class="info-card">
                <div class="info-item">
                    <div class="info-label"> Специальность</div>
                    <div class="info-value"><?php echo htmlspecialchars($currentProgram['specialty'] ?? '—'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label"> Часы</div>
                    <div class="info-value"><?php echo $currentProgram['hours'] ?? '0'; ?> ч.</div>
                </div>
                <div class="info-item">
                    <div class="info-label"> Стоимость</div>
                    <div class="info-value price-value"><?php echo formatPrice($currentProgram['price'] ?? 0); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">️ Кафедра</div>
                    <div class="info-value"><?php echo htmlspecialchars($currentProgram['division'] ?? '—'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label"> Тип программы</div>
                    <div class="info-value"><?php echo htmlspecialchars($currentProgram['programtype'] ?? '—'); ?></div>
                </div>
            </div>
            

            <?php if (!empty($currentProgram['description'])): ?>
            <div class="detail-section">
                <div class="section-title"> О программе</div>
                <div class="section-content"><?php echo nl2br(htmlspecialchars($currentProgram['description'])); ?></div>
            </div>
            <?php endif; ?>
            

            <?php if (!empty($currentProgram['goals'])): ?>
            <div class="detail-section">
                <div class="section-title">Цель программы</div>
                <div class="section-content"><?php echo nl2br(htmlspecialchars($currentProgram['goals'])); ?></div>
            </div>
            <?php endif; ?>
            

            <?php if (!empty($currentProgram['нyperlinkNMO'])): ?>
            <div class="detail-section">
                <div class="section-title"> Полезная ссылка</div>
                <div class="section-content">
                    <a href="<?php echo htmlspecialchars($currentProgram['нyperlinkNMO']); ?>" target="_blank">Перейти к материалам →</a>
                </div>
            </div>
            <?php endif; ?>
            

            <div class="btn-group">
                <a href="index.php" class="btn-back">Записаться на курс</a>
            </div>
        </div>
    </div>
</body>
</html>