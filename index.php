<?php
$jsonString = file_get_contents('ProgramWebsite.jsn');
if ($jsonString === false) {
    die('Файл не найден');
}
$allPrograms = json_decode($jsonString, true);
?>

    <link rel="stylesheet" href="style14.css">
<body>
<div class="filters">
            <input type="searchfield" id="searchInput" placeholder="Поиск по названию...">

            <select id="priceFilter">
                <option value="all">Цена: Все</option>
                <option value="free">Бесплатные</option>
                <option value="paid">Платные</option>
            </select>

            <select id="hoursFilter">
                <option value="all">Часы: Все</option>
                <option value="16">16</option>
                <option value="18">18</option>
                <option value="24">24</option>
                <option value="30">30</option>
                <option value="36">36</option>
                <option value="72">72</option>
                <option value="144">144</option>
                <option value="216">216</option>
                <option value="288">288</option>
                <option value="432">432</option>
                <option value="504">504</option>
                <option value="576">576</option>
                <option value="720">720</option>
                <option value="864">864</option>
                <option value="990">990</option>
                <option value="1008">1008</option>
                <option value="1296">1296</option>
            </select>

            <select id="sortOrder">
                <option value="default">Сортировка: по умолчанию</option>
                <option value="name-asc">По названию (А-Я)</option>
                <option value="price-asc">По цене (сначала дешёвые)</option>
                <option value="hours-asc">По длительности (сначала короткие)</option>
            </select>
        </div>
    <div class="container1">

        <div id="catalog" class="catalog"></div>

        <div style="text-align: center; margin-top: 40px;">
            <button id="loadMoreBtn" class="card-btn">
                Показать ещё
            </button>
        </div>
    </div>

    <script>
        const programs = <?php echo json_encode($allPrograms); ?>;
        let filteredPrograms = [...programs];
        let index = 0;
        const perPage = 12;

        function escapeHtml(text) {л
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
                    <p><a href="index1.php?id=${encodeURIComponent(p.id)}" class="card-btn">Подробнее</a></p>
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
                if (hoursFilter === '16') hoursMatch = hours === 16;
                if (hoursFilter === '18') hoursMatch = hours === 18;
                if (hoursFilter === '24') hoursMatch = hours === 24;
                if (hoursFilter === '30') hoursMatch = hours === 30;
                if (hoursFilter === '36') hoursMatch = hours === 36;
                if (hoursFilter === '72') hoursMatch = hours === 72;
                if (hoursFilter === '144') hoursMatch = hours === 144;
                if (hoursFilter === '216') hoursMatch = hours === 216;
                if (hoursFilter === '288') hoursMatch = hours === 288;
                if (hoursFilter === '432') hoursMatch = hours === 432;
                if (hoursFilter === '504') hoursMatch = hours === 504;
                if (hoursFilter === '576') hoursMatch = hours === 576;
                if (hoursFilter === '720') hoursMatch = hours === 720;
                if (hoursFilter === '864') hoursMatch = hours === 864;
                if (hoursFilter === '990') hoursMatch = hours === 990;
                if (hoursFilter === '1008') hoursMatch = hours === 1008;
                if (hoursFilter === '1296') hoursMatch = hours === 1296;

                return nameMatch && priceMatch && hoursMatch;
            });

            if (sort === 'name-asc') {
                filteredPrograms.sort((a, b) => (a.name || '').localeCompare(b.name || ''));
            } else if (sort === 'price-asc') {
                filteredPrograms.sort((a, b) => (a.price || 0) - (b.price || 0));
            } else if (sort === 'hours-asc') {
                filteredPrograms.sort((a, b) => (a.hours || 0) - (b.hours || 0));
            }

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

        document.getElementById('searchInput').addEventListener('input', applyFilters);
        document.getElementById('priceFilter').addEventListener('change', applyFilters);
        document.getElementById('hoursFilter').addEventListener('change', applyFilters);
        document.getElementById('sortOrder').addEventListener('change', applyFilters);

        // Первая загрузка
        loadMore();
        document.getElementById('loadMoreBtn').addEventListener('click', loadMore);
    </script>
</body>