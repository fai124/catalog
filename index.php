<?php
$jsonString = file_get_contents('ProgramWebsite.jsn');
if ($jsonString === false) {
    die('Файл не найден');
}
$allPrograms = json_decode($jsonString, true);
?>

    <link rel="stylesheet" href="style.css">
    <style>
        .filters {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .filters select, .filters input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .filters input {
            min-width: 200px;
        }
    </style>
<body>
    <div class="container">
        <!-- Фильтры -->
        <div class="filters">
            <input type="text" id="searchInput" placeholder="Поиск по названию...">

            <select id="priceFilter">
                <option value="all">Цена: Все</option>
                <option value="free">Бесплатные</option>
                <option value="paid">Платные</option>
            </select>

            <select id="hoursFilter">
                <option value="all">Часы: Все</option>
                <option value="lt10">Менее 10 ч</option>
                <option value="10to50">10–50 ч</option>
                <option value="gt50">Более 50 ч</option>
            </select>

            <select id="sortOrder">
                <option value="default">Сортировка: по умолчанию</option>
                <option value="name-asc">По названию (А-Я)</option>
                <option value="price-asc">По цене (сначала дешёвые)</option>
                <option value="hours-asc">По длительности (сначала короткие)</option>
            </select>
        </div>

        <!-- Каталог -->
        <div id="catalog" class="catalog"></div>

        <!-- Кнопка "Показать ещё" -->
        <div style="text-align: center; margin-top: 40px;">
            <button id="loadMoreBtn" class="card-btn" style="background:#28a745; padding:12px 30px; font-size:16px;">
                Показать ещё
            </button>
        </div>
    </div>

    <script>
        const programs = <?php echo json_encode($allPrograms); ?>;
        let filteredPrograms = [...programs];
        let index = 0;
        const perPage = 12;

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function renderCard(p) {
            const price = (p.price || 0) > 0 
                ? new Intl.NumberFormat('ru-RU').format(p.price) + ' ₽' 
                : 'Бесплатно';

            return `
                <div class="card">
                    <img src="test1.jpg" alt="Курс" class="card-image">
                    <h3 class="card-title">${escapeHtml(p.name || 'Без названия')}</h3>
                    <p class="card-hours">${p.hours || 0} ч.</p>
                    <p class="card-price"><strong>Цена:</strong> ${price}</p>
                    <a href="index1.php" class="card-btn">Подробнее →</a>
                </div>
            `;
        }

        function applyFilters() {
            const search = document.getElementById('searchInput').value.toLowerCase().trim();
            const priceFilter = document.getElementById('priceFilter').value;
            const hoursFilter = document.getElementById('hoursFilter').value;
            const sort = document.getElementById('sortOrder').value;

            filteredPrograms = programs.filter(p => {
                const nameMatch = !search || (p.name && p.name.toLowerCase().includes(search));
                
                let priceMatch = true;
                if (priceFilter === 'free') priceMatch = (p.price || 0) === 0;
                if (priceFilter === 'paid') priceMatch = (p.price || 0) > 0;

                let hoursMatch = true;
                const hours = p.hours || 0;
                if (hoursFilter === 'lt10') hoursMatch = hours < 10;
                if (hoursFilter === '10to50') hoursMatch = hours >= 10 && hours <= 50;
                if (hoursFilter === 'gt50') hoursMatch = hours > 50;

                return nameMatch && priceMatch && hoursMatch;
            });

            // Сортировка
            if (sort === 'name-asc') {
                filteredPrograms.sort((a, b) => (a.name || '').localeCompare(b.name || ''));
            } else if (sort === 'price-asc') {
                filteredPrograms.sort((a, b) => (a.price || 0) - (b.price || 0));
            } else if (sort === 'hours-asc') {
                filteredPrograms.sort((a, b) => (a.hours || 0) - (b.hours || 0));
            }

            // Перезагружаем каталог
            reloadCatalog();
        }

        function reloadCatalog() {
            document.getElementById('catalog').innerHTML = '';
            index = 0;
            loadMore();
        }

        function loadMore() {
            const fragment = document.createDocumentFragment();
            const nextBatch = filteredPrograms.slice(index, index + perPage);

            if (nextBatch.length === 0 && index === 0) {
                document.getElementById('catalog').innerHTML = '<p style="text-align:center; color:#666;">Нет программ по вашему запросу.</p>';
            }

            if (nextBatch.length === 0) {
                document.getElementById('loadMoreBtn').style.display = 'none';
                return;
            }

            nextBatch.forEach(program => {
                const card = document.createElement('div');
                card.innerHTML = renderCard(program);
                fragment.appendChild(card.firstElementChild);
            });

            document.getElementById('catalog').appendChild(fragment);
            index += nextBatch.length;

            const remaining = filteredPrograms.length - index;
            const btn = document.getElementById('loadMoreBtn');
            btn.textContent = remaining > 0 ? 'Показать ещё' : 'Все программы загружены';
            btn.disabled = remaining === 0;
            btn.style.opacity = remaining === 0 ? '0.5' : '1';
            btn.style.display = 'block';
        }

        // Привязка событий
        document.getElementById('searchInput').addEventListener('input', applyFilters);
        document.getElementById('priceFilter').addEventListener('change', applyFilters);
        document.getElementById('hoursFilter').addEventListener('change', applyFilters);
        document.getElementById('sortOrder').addEventListener('change', applyFilters);

        // Первая загрузка
        loadMore();
        document.getElementById('loadMoreBtn').addEventListener('click', loadMore);
    </script>
</body>
</html>