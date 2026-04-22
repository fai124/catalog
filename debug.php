<?php
$jsonString = file_get_contents('ProgramWebsite.jsn');

echo '<h3>1. Файл прочитался?</h3>';
if ($jsonString === false) {
    die('Файл не найден!');
}
echo '✅ Файл прочитался, размер: ' . strlen($jsonString) . ' байт<br>';

echo '<h3>2. Содержимое файла (первые 500 символов):</h3>';
echo '<pre>' . htmlspecialchars(mb_substr($jsonString, 0, 500)) . '</pre>';

echo '<h3>3. Результат json_decode():</h3>';
$data = json_decode($jsonString, true);
var_dump($data);

echo '<h3>4. Ошибка JSON:</h3>';
echo json_last_error_msg();
?>