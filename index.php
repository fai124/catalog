<?php
$jsonString = file_get_contents(ProgramWebsite.jsn);
if($jsonString === false) {
    die('файл не найден');
}
echo '<pre>' . htmlspecialchars($jsonString) . '</pre>'
?>