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

                optionButton.addEventListener('click', function (e) {
                    e.stopPropagation();

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

function syncCityVisibility() {
    const countrySelect = document.getElementById('country');
    const citySelect = document.getElementById('city');
    const cityContainer = document.getElementById('city-select-container');

    if (!countrySelect || !cityContainer) return;

    const cityWrapper = citySelect ? citySelect.closest('.custom-select') : null;

    if (countrySelect.value) {
        cityContainer.style.display = 'block';

        if (cityWrapper) {
            cityWrapper.classList.remove('hidden');
            cityWrapper.classList.add('show');
        }
    } else {
        if (citySelect) {
            citySelect.value = '';
        }

        if (cityWrapper) {
            cityWrapper.classList.remove('show');
            cityWrapper.classList.add('hidden');
        }
    }
}

$(document).ready(function () {
    initializeCustomSelects(document);
    syncCityVisibility();

    function loadUniversity(page = 1) {
        $.ajax({
            url: "/universities",
            method: "GET",
            data: {
                search: $('#search').val(),
                city: $('#city').val(),
                country: $('#country').val(),
                rank: $('#rank').val(),
                sort: $('#sort').val(),
                page: page
            },
            success: function (response) {
                $('#university-table').html(response.table);
                $('#city-select-container').html(response.citySelect);

                initializeCustomSelects(document.getElementById('filter_section'));
                initializeCustomSelects(document.getElementById('university-table'));

                syncCityVisibility();
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', error);
                console.log(xhr.responseText);
            }
        });
    }

    $('#search').on('keyup', function () {
        loadUniversity(1);
    });

    $(document).on('change', '#country, #city, #rank, #sort', function () {
        loadUniversity(1);
    });

    $(document).on('click', '.pagination-link', function (e) {
        e.preventDefault();

        const page = $(this).data('page');
        loadUniversity(page);
    });

    document.addEventListener('click', function () {
        document.querySelectorAll('.custom-select').forEach((item) => {
            item.classList.remove('open');
        });
    });
});