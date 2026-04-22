<?php
$jsonString = file_get_contents('ProgramWebsite.jsn');
if($jsonString === false) die('файл не найден');

$allPrograms = json_decode($jsonString, true);
$totalPrograms = count($allPrograms);
?>

    <link rel="stylesheet" href="style.css">
<body>
    <div class="container">
        <div id="catalog" class="catalog"></div>
        <div style="text-align: center; margin-top: 40px;">
            <button id="loadMoreBtn" class="card-btn" style="background:#28a745; padding:12px 30px; font-size:16px;">Показать ещё (12)</button>
        </div>
    </div>
    <script>
        const programs = <?php echo json_encode($allPrograms); ?>;
        let index = 0;
        const perPage = 12;

        function renderCard(p) {
            const price = (p.price || 0) > 0 ? new Intl.NumberFormat('ru-RU').format(p.price) + ' ₽' : 'Бесплатно';
            return `
                <div class="card">
                    <img src="test1.jpg" class="card-image">
                    <h3 class="card-title">${escapeHtml(p.name || 'Без названия')}</h3>
                    <p class="card-hours">⏱️ ${p.hours || 0} ч.</p>
                    <p class="card-price"><strong>Цена:</strong> ${price}</p>
                    <a href="index1.php" class="card-btn">Подробнее →</a>
                </div>
            `;
        }

        function escapeHtml(t) {
            const div = document.createElement('div');
            div.textContent = t;
            return div.innerHTML;
        }

        function loadMore() {
            const next = programs.slice(index, index + perPage);
            if (next.length === 0) {
                document.getElementById('loadMoreBtn').style.display = 'none';
                return;
            }
            next.forEach(p => {
                document.getElementById('catalog').innerHTML += renderCard(p);
            });
            index += next.length;
            const btn = document.getElementById('loadMoreBtn');
            const remaining = programs.length - index;
            if (remaining > 0) {
                btn.textContent = `Показать ещё`;
            } else {
                btn.textContent = 'Все программы загружены';
                btn.disabled = true;
                btn.style.opacity = '0.5';
            }
        }

        loadMore();
        document.getElementById('loadMoreBtn').onclick = loadMore;
    </script>
</body>
</html>