$(document).ready(function () {
    let searchTimeout = null;

    function loadPrograms(page = 1) {
        const url = $('#program-table').data('url');
        if (!url) return;

        $.ajax({
            url: url,
            method: 'GET',
            data: {
                searchProgram: $('#searchProgram').val(),
                category: $('#category').val(),
                level: $('#level').val(),
                intensity: $('#intensity').val(),
                mode: $('#mode').val(),
                page: page
            },
            success: function (response) {
                $('#program-table').html(response);
            },
            error: function (xhr, status, error) {
                console.error('Programs AJAX failed:', error);
            }
        });
    }

    $('#searchProgram').on('keyup', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function () {
            loadPrograms(1);
        }, 300);
    });

    $(document).on('change', '#category, #level, #intensity, #mode', function () {
        loadPrograms(1);
    });

    $(document).on('click', '.program-pagination-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            loadPrograms(page);
        }
    });
});

/* =========================
   Custom Selects
========================= */
function initializeCustomSelects(scope = document) {
    const nativeSelects = scope.querySelectorAll('select.select:not([data-enhanced="true"])');

    nativeSelects.forEach((nativeSelect) => {
        nativeSelect.setAttribute('data-enhanced', 'true');
        nativeSelect.classList.add('native-select');

        const wrapper = document.createElement('div');
        wrapper.className = 'custom-select';

        if (nativeSelect.classList.contains('hidden')) {
            wrapper.classList.add('hidden');
        }

        if (nativeSelect.classList.contains('show')) {
            wrapper.classList.add('show');
        }

        nativeSelect.parentNode.insertBefore(wrapper, nativeSelect);
        wrapper.appendChild(nativeSelect);

        const trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'select-trigger';

        const label = document.createElement('span');
        label.className = 'select-trigger-text';

        const arrow = document.createElement('span');
        arrow.className = 'select-arrow';

        trigger.appendChild(label);
        trigger.appendChild(arrow);

        const menu = document.createElement('div');
        menu.className = 'select-menu';

        wrapper.appendChild(trigger);
        wrapper.appendChild(menu);

        function updateTriggerText() {
            const selectedOption = nativeSelect.options[nativeSelect.selectedIndex];
            label.textContent = selectedOption ? selectedOption.textContent : 'Select';
        }

        function buildOptions() {
            menu.innerHTML = '';

            Array.from(nativeSelect.options).forEach((option) => {
                const optionButton = document.createElement('button');
                optionButton.type = 'button';
                optionButton.className = 'select-option';
                optionButton.textContent = option.textContent;
                optionButton.dataset.value = option.value;

                if (option.selected) {
                    optionButton.classList.add('selected');
                }

                optionButton.addEventListener('click', function () {
                    nativeSelect.value = option.value;

                    Array.from(menu.querySelectorAll('.select-option')).forEach((btn) => {
                        btn.classList.remove('selected');
                    });

                    optionButton.classList.add('selected');
                    updateTriggerText();
                    wrapper.classList.remove('open');

                    nativeSelect.dispatchEvent(new Event('change', { bubbles: true }));
                });

                menu.appendChild(optionButton);
            });

            updateTriggerText();
        }

        trigger.addEventListener('click', function (e) {
            e.stopPropagation();

            document.querySelectorAll('.custom-select').forEach((item) => {
                if (item !== wrapper) {
                    item.classList.remove('open');
                }
            });

            wrapper.classList.toggle('open');
        });

        nativeSelect.addEventListener('change', function () {
            buildOptions();
        });

        buildOptions();
    });
}

document.addEventListener('click', function () {
    document.querySelectorAll('.custom-select').forEach((item) => {
        item.classList.remove('open');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    initializeCustomSelects(document);

    const countrySelect = document.getElementById('country');
    const citySelect = document.getElementById('city');

    if (countrySelect && citySelect) {
        function toggleCityVisibility() {
            const cityWrapper = citySelect.closest('.custom-select');
            if (!cityWrapper) return;

            if (countrySelect.value) {
                cityWrapper.classList.remove('hidden');
                cityWrapper.classList.add('show');
            } else {
                cityWrapper.classList.remove('show');
                cityWrapper.classList.add('hidden');
                citySelect.value = '';
                citySelect.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }

        toggleCityVisibility();
        countrySelect.addEventListener('change', toggleCityVisibility);
    }
});

/* =========================
   Favorite Toggle
========================= */
document.addEventListener('click', async function (e) {
    const favoriteBtn = e.target.closest('.favorite-toggle');
    if (!favoriteBtn) return;

    e.preventDefault();
    e.stopPropagation();

    const icon = favoriteBtn.querySelector('.saveIcon');
    const saveUrl = favoriteBtn.dataset.saveUrl;
    const unsavedIcon = favoriteBtn.dataset.unsavedIcon;
    const savedIcon = favoriteBtn.dataset.savedIcon;

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfMeta) {
        console.error('CSRF token meta tag not found.');
        return;
    }

    const csrfToken = csrfMeta.getAttribute('content');

    try {
        const response = await fetch(saveUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (response.status === 401 && data.requires_login) {
            window.location.href = data.login_url;
            return;
        }

        if (!response.ok) {
            console.error(data.message || 'Favorite toggle failed.');
            return;
        }

        if (icon) {
            icon.src = data.saved ? savedIcon : unsavedIcon;
        }
    } catch (error) {
        console.error('Favorite toggle failed:', error);
    }
});

/* =========================
   Prevent details toggle when clicking favorite
========================= */
document.addEventListener('mousedown', function (e) {
    const favoriteBtn = e.target.closest('.favorite-toggle');
    if (!favoriteBtn) return;

    e.preventDefault();
});