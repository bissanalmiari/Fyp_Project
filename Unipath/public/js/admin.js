document.addEventListener("DOMContentLoaded", function () {

    let timeout = null;

    // ==============================
    // FETCH FUNCTION (GLOBAL)
    // ==============================
    function fetchData(config, url = null) {

        let endpoint = url ? new URL(url) : new URL(window.location.href);

        // SEARCH
        if (config.searchId) {
            const searchEl = document.getElementById(config.searchId);
            if (searchEl) {
                endpoint.searchParams.set('search', searchEl.value);

                // reset page only on typing (not pagination)
                if (!url) {
                    endpoint.searchParams.delete('page');
                }
            }
        }

        // CATEGORY
        if (config.categoryId) {
            const categoryEl = document.getElementById(config.categoryId);
            if (categoryEl && !url) {
                endpoint.searchParams.set('category_id', categoryEl.value);
                endpoint.searchParams.delete('page');
            }
        }

        fetch(endpoint.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {

            // ==============================
            // GRID UPDATE
            // ==============================
            if (config.gridId && data.html !== undefined) {
                const grid = document.getElementById(config.gridId);
                if (grid) {
                    grid.innerHTML = data.html;
                }
            }

            // ==============================
            // PAGINATION UPDATE
            // ==============================
            if (config.paginationId) {
                const pagination = document.getElementById(config.paginationId);
                if (pagination && data.pagination !== undefined) {
                    pagination.innerHTML = data.pagination;
                }
            }

            // ==============================
            // COUNT UPDATE
            // ==============================
            if (config.countId) {
                const count = document.getElementById(config.countId);
                if (count && data.count !== undefined) {
                    count.innerText = data.count;
                }
            }

            // ==============================
            // RANGE UPDATE
            // ==============================
            if (config.rangeId) {
                const range = document.getElementById(config.rangeId);
                if (range && data.from !== undefined) {
                    range.innerText = `${data.from ?? 0}–${data.to ?? 0}`;
                }
            }
        })
        .catch(err => console.error("AJAX Error:", err));
    }

    // ==============================
    // CAREERS PAGE
    // ==============================
    if (document.getElementById('careers-grid')) {

        const careersConfig = {
            gridId: 'careers-grid',
            paginationId: 'pagination-wrapper',
            countId: 'careers-count',
            rangeId: 'range',
            searchId: 'search',
            categoryId: 'category'
        };

        const searchInput = document.getElementById('search');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => fetchData(careersConfig), 300);
            });
        }

        const categorySelect = document.getElementById('category');
        if (categorySelect) {
            categorySelect.addEventListener('change', function () {
                fetchData(careersConfig);
            });
        }

        document.addEventListener('click', function (e) {
            const link = e.target.closest('#pagination-wrapper a');
            if (!link) return;

            e.preventDefault();
            fetchData(careersConfig, link.href);
        });
    }

    // ==============================
    // USERS PAGE
    // ==============================
    if (document.getElementById('users-grid')) {

        const usersConfig = {
            gridId: 'users-grid',
            paginationId: 'pagination-wrapper',
            searchId: 'searchInput'
        };

        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => fetchData(usersConfig), 300);
            });
        }

        document.addEventListener('click', function (e) {
            const link = e.target.closest('#pagination-wrapper a');
            if (!link) return;

            e.preventDefault();
            fetchData(usersConfig, link.href);
        });
    }

    // ==============================
    // SIDEBAR TOGGLE
    // ==============================
    const openBtn = document.getElementById("menuToggle");
    const closeBtn = document.getElementById("closeSidebar");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");

    function openSidebar() {
        sidebar?.classList.remove("-translate-x-full");
        overlay?.classList.remove("hidden");
    }

    function closeSidebar() {
        sidebar?.classList.add("-translate-x-full");
        overlay?.classList.add("hidden");
    }

    openBtn?.addEventListener("click", openSidebar);
    closeBtn?.addEventListener("click", closeSidebar);
    overlay?.addEventListener("click", closeSidebar);

});