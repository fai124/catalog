<?php
$programId = $_GET['id'] ?? '';

if (empty($programId)) {
    die('ID программы не указан');
}

$jsonString = file_get_contents('ProgramWebsite1.jsn');
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


function formatPrice($price) {
    if ($price > 0) {
        return number_format($price, 0, ',', ' ') . ' ₽';
    }
    return 'Бесплатно';
}


if (isset($GLOBALS['APPLICATION'])) {

    $_POST['form_text_9'] = $currentProgram['name']; 
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($currentProgram['name'] ?? 'Программа'); ?></title>
    <link rel="stylesheet" href="styleIndex30.css">
</head>
<body>
    <div class="container">
        <div class="detail-container">
            <a href="index.php" class="back-link">← Назад к каталогу</a>
            
            <h1 class="detail-title"><?php echo htmlspecialchars($currentProgram['name'] ?? 'Без названия'); ?></h1>
            
            <div class="info-card">
                <div class="info-item">
                    <div class="info-label"> Часы</div>
                    <div class="info-value"><?php echo $currentProgram['hours'] ?? '0'; ?> ч.</div>
                </div>
                <div class="info-item">
                    <div class="info-label"> Стоимость</div>
                    <div class="info-value price-value"><?php echo formatPrice($currentProgram['price'] ?? 0); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label"> Специальность</div>
                    <div class="info-value"><?php echo htmlspecialchars($currentProgram['specialty'] ?? '—'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">️Кафедра</div>
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
                <button id="openFormBtn" class="btn-back" style="background:#06692c; border:none; cursor:pointer;">Записаться на курс</button>
            </div>
        </div>
    </div>

    <div id="modalForm" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; max-width: 550px; width: 90%; border-radius: 16px; padding: 30px; position: relative; max-height: 90%; overflow-y: auto;">
            
            <!-- Кнопка закрытия -->
            <button id="closeModalBtn" style="position: absolute; top: 10px; right: 15px; background: none; border: none; font-size: 28px; cursor: pointer;">&times;</button>
            
            <h3 style="margin-bottom: 15px; color: #06692c;"> Запись на курс</h3>
            <p style="margin-bottom: 20px; padding: 10px; background: #f0f7f0; border-radius: 8px;">
                <strong>Вы выбрали программу:</strong><br>
                <?php echo htmlspecialchars($currentProgram['name'] ?? 'Без названия'); ?>
            </p>
            
            <?php
            
            $APPLICATION->IncludeComponent(
                "bitrix:form.result.new",
                ".default",
                array(
                    "WEB_FORM_ID" => "2",
                    "AJAX_MODE" => "N",
                    "SUCCESS_URL" => "/index.php",
                    "CHAIN_ITEM_TEXT" => "",
                    "CHAIN_ITEM_LINK" => "",
                    "IGNORE_CUSTOM_TEMPLATE" => "N",
                    "USE_EXTENDED_ERRORS" => "Y",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "3600",
                )
            );
            ?>
<br>
<div class="agreement-text" style="text-align: center;">Нажимая на кнопку, вы даете согласие на обработку персональных данных и соглашаетесь c <a href="https://susmu.su/1_Файлы/Отдел%20ТСО/dokumenty_2024/Политика%20по%20защите%20ПДн%20_12.04.2024%20с%20ИИ010.2024%20ЭП.pdf" target="_blank"> политикой конфиденциальности</a></div>
        </div>
    </div>

    <script>
        var openBtn = document.getElementById('openFormBtn');
        if (openBtn) {
            openBtn.addEventListener('click', function() {
                document.getElementById('modalForm').style.display = 'flex';
            });
        }
        

        var closeBtn = document.getElementById('closeModalBtn');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                document.getElementById('modalForm').style.display = 'none';
            });
        }
        

        window.addEventListener('click', function(event) {
            var modal = document.getElementById('modalForm');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        setInterval(function() {
    var agreement = document.querySelector('.agreement-text');
    var modal = document.getElementById('modalForm');
    
    if (agreement && modal && modal.innerText.includes('успешно')) {
        agreement.style.display = 'none';
    }
}, 500);
    </script>
</body>
</html>