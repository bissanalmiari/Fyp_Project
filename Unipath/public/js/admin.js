document.addEventListener("DOMContentLoaded", function () {

    let timeout = null;

    //  FETCH FUNCTION
    function fetchData(config, url = null) {

        let endpoint = url ? new URL(url) : new URL(window.location.href);

        // SEARCH
        if (config.searchId) {
            const searchEl = document.getElementById(config.searchId);
            if (searchEl && !url) {
                endpoint.searchParams.set('search', searchEl.value);
                endpoint.searchParams.delete('page');
            }
        }

        
        if (config.categoryId) {
            const categoryEl = document.getElementById(config.categoryId);
            if (categoryEl && !url) {
                endpoint.searchParams.set('category_id', categoryEl.value);
                endpoint.searchParams.delete('page');
            }
        }

        fetch(endpoint.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {

            if (config.gridId && data.html) {
                document.getElementById(config.gridId).innerHTML = data.html;
            }

           if (config.paginationId) {
    document.getElementById(config.paginationId).innerHTML = data.pagination || '';
}

            if (config.countId && data.count !== undefined) {
                document.getElementById(config.countId).innerText = data.count;
            }

            if (config.rangeId && data.from !== undefined) {
                document.getElementById(config.rangeId).innerText =
                    `${data.from ?? 0}–${data.to ?? 0}`;
            }
        });
    }

 
    //  CAREERS PAGE
   
    if (document.getElementById('careers-grid')) {

        const careersConfig = {
            gridId: 'careers-grid',
            paginationId: 'pagination-wrapper',
            countId: 'careers-count',
            rangeId: 'range',
            searchId: 'search',
            categoryId: 'category'
        };

        // SEARCH
        const searchInput = document.getElementById('search');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => fetchData(careersConfig), 300);
            });
        }

        // CATEGORY
        const categorySelect = document.getElementById('category');
        if (categorySelect) {
            categorySelect.addEventListener('change', function () {
                fetchData(careersConfig);
            });
        }

        // PAGINATION
        document.addEventListener('click', function (e) {
            const link = e.target.closest('#pagination-wrapper a');
            if (!link) return;

            e.preventDefault();
            fetchData(careersConfig, link.href);
        });
    }

    
    //  USERS PAGE
   
    if (document.getElementById('users-grid')) {

        const usersConfig = {
            gridId: 'users-grid',
            paginationId: 'pagination-wrapper',
            searchId: 'searchInput'
        };

        // SEARCH
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => fetchData(usersConfig), 300);
            });
        }

        // PAGINATION
        document.addEventListener('click', function (e) {
            const link = e.target.closest('#pagination-wrapper a');
            if (!link) return;

            e.preventDefault();
            fetchData(usersConfig, link.href);
        });
    }

});