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
    // UNIVERSITIES PAGE
    // ==============================
    if (document.getElementById('universities-grid')) {

        function fetchUniversities(url = null) {
            const grid = document.getElementById('universities-grid');
            if (!grid) return;

            let endpoint = url ? new URL(url) : new URL(window.location.href);

            const search = document.getElementById('university-search')?.value || '';
            const country = document.getElementById('university-country')?.value || '';
            const city = document.getElementById('university-city')?.value || '';
            const type = document.getElementById('university-type')?.value || '';

            if (!url) {
                endpoint.searchParams.delete('page');
            }

            if (search) {
                endpoint.searchParams.set('search', search);
            } else {
                endpoint.searchParams.delete('search');
            }

            if (country) {
                endpoint.searchParams.set('country', country);
            } else {
                endpoint.searchParams.delete('country');
            }

            if (city) {
                endpoint.searchParams.set('city', city);
            } else {
                endpoint.searchParams.delete('city');
            }

            if (type) {
                endpoint.searchParams.set('type', type);
            } else {
                endpoint.searchParams.delete('type');
            }

            fetch(endpoint.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                const pagination = document.getElementById('universities-pagination');
                const count = document.getElementById('universities-count');
                const range = document.getElementById('universities-range');

                if (grid) grid.innerHTML = data.html;
                if (pagination) pagination.innerHTML = data.pagination;
                if (count) count.innerText = data.count;
                if (range) range.innerText = `${data.from ?? 0}–${data.to ?? 0}`;
            })
            .catch(error => console.error('Universities AJAX error:', error));
        }

        const universitySearch = document.getElementById('university-search');
        if (universitySearch) {
            universitySearch.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => fetchUniversities(), 300);
            });
        }

        const universityCountry = document.getElementById('university-country');
        if (universityCountry) {
            universityCountry.addEventListener('change', function () {
                fetchUniversities();
            });
        }

        const universityCity = document.getElementById('university-city');
        if (universityCity) {
            universityCity.addEventListener('change', function () {
                fetchUniversities();
            });
        }

        const universityType = document.getElementById('university-type');
        if (universityType) {
            universityType.addEventListener('change', function () {
                fetchUniversities();
            });
        }

        document.addEventListener('click', function (e) {
            const link = e.target.closest('#universities-pagination a');
            if (!link) return;

            e.preventDefault();
            fetchUniversities(link.href);
        });
    }

    // ==============================
    // PROGRAMS PAGE
    // ==============================
    if (document.getElementById('programs-grid')) {
        let programTimeout = null;

        function fetchPrograms(url = null) {
            const grid = document.getElementById('programs-grid');
            if (!grid) return;

            let endpoint = url ? new URL(url) : new URL(window.location.href);

            const search = document.getElementById('program-search')?.value || '';
            const university = document.getElementById('program-university')?.value || '';
            const category = document.getElementById('program-category')?.value || '';
            const subcategory = document.getElementById('program-subcategory')?.value || '';
            const level = document.getElementById('program-level')?.value || '';
            const studyMode = document.getElementById('program-study-mode')?.value || '';
            const courseIntensity = document.getElementById('program-course-intensity')?.value || '';

            if (!url) {
                endpoint.searchParams.delete('page');
            }

            if (search) {
                endpoint.searchParams.set('search', search);
            } else {
                endpoint.searchParams.delete('search');
            }

            if (university) {
                endpoint.searchParams.set('university_id', university);
            } else {
                endpoint.searchParams.delete('university_id');
            }

            if (category) {
                endpoint.searchParams.set('category_id', category);
            } else {
                endpoint.searchParams.delete('category_id');
            }

            if (subcategory) {
                endpoint.searchParams.set('subcategory_id', subcategory);
            } else {
                endpoint.searchParams.delete('subcategory_id');
            }

            if (level) {
                endpoint.searchParams.set('level', level);
            } else {
                endpoint.searchParams.delete('level');
            }

            if (studyMode) {
                endpoint.searchParams.set('study_mode', studyMode);
            } else {
                endpoint.searchParams.delete('study_mode');
            }

            if (courseIntensity) {
                endpoint.searchParams.set('course_intensity', courseIntensity);
            } else {
                endpoint.searchParams.delete('course_intensity');
            }

            fetch(endpoint.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                const pagination = document.getElementById('programs-pagination');
                const count = document.getElementById('programs-count');
                const range = document.getElementById('programs-range');

                if (grid) grid.innerHTML = data.html;
                if (pagination) pagination.innerHTML = data.pagination;
                if (count) count.innerText = data.count;
                if (range) range.innerText = `${data.from ?? 0}–${data.to ?? 0}`;
            })
            .catch(error => console.error('Programs AJAX error:', error));
        }

        const programSearch = document.getElementById('program-search');
        if (programSearch) {
            programSearch.addEventListener('input', function () {
                clearTimeout(programTimeout);
                programTimeout = setTimeout(() => fetchPrograms(), 300);
            });
        }

        const programUniversity = document.getElementById('program-university');
        if (programUniversity) {
            programUniversity.addEventListener('change', function () {
                fetchPrograms();
            });
        }

        const programCategory = document.getElementById('program-category');
        const programSubcategory = document.getElementById('program-subcategory');

        function filterProgramSubcategories() {
            if (!programCategory || !programSubcategory) return;

            const selectedCategoryId = programCategory.value;
            const selectedSubcategoryId = programSubcategory.value;

            Array.from(programSubcategory.options).forEach(option => {
                if (option.value === '') {
                    option.hidden = false;
                    return;
                }

                const belongsToCategory = option.dataset.category === selectedCategoryId;
                option.hidden = !selectedCategoryId || !belongsToCategory;
            });

            programSubcategory.disabled = !selectedCategoryId;

            if (selectedSubcategoryId) {
                const selectedOption = Array.from(programSubcategory.options).find(
                    option => option.value === selectedSubcategoryId
                );

                if (!selectedOption || selectedOption.dataset.category !== selectedCategoryId) {
                    programSubcategory.value = '';
                }
            }
        }

        if (programCategory) {
            programCategory.addEventListener('change', function () {
                filterProgramSubcategories();
                fetchPrograms();
            });

            filterProgramSubcategories();
        }

        if (programSubcategory) {
            programSubcategory.addEventListener('change', function () {
                fetchPrograms();
            });
        }

        const programLevel = document.getElementById('program-level');
        if (programLevel) {
            programLevel.addEventListener('change', function () {
                fetchPrograms();
            });
        }

        const programStudyMode = document.getElementById('program-study-mode');
        if (programStudyMode) {
            programStudyMode.addEventListener('change', function () {
                fetchPrograms();
            });
        }

        const programCourseIntensity = document.getElementById('program-course-intensity');
        if (programCourseIntensity) {
            programCourseIntensity.addEventListener('change', function () {
                fetchPrograms();
            });
        }

        document.addEventListener('click', function (e) {
            const link = e.target.closest('#programs-pagination a');
            if (!link) return;

            e.preventDefault();
            fetchPrograms(link.href);
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