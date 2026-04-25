document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('comparison-filter-form');
    const comparisonContainer = document.getElementById('comparison-section-container');
    let comparedState = false;

    function initComparisonDropdowns(scope = document) {
        const dropdowns = scope.querySelectorAll('.compare-dropdown:not([data-bound="true"])');

        dropdowns.forEach((dropdown) => {
            dropdown.dataset.bound = 'true';

            const trigger = dropdown.querySelector('.dropdown-trigger');
            const panel = dropdown.querySelector('.dropdown-panel');
            const searchInput = dropdown.querySelector('.dropdown-search');
            const optionLabels = dropdown.querySelectorAll('.dropdown-option');
            const applyBtn = dropdown.querySelector('.dropdown-apply');
            const resetBtn = dropdown.querySelector('.dropdown-reset');
            const selectedLabel = dropdown.querySelector('.selected-label');

            const targetInputId = dropdown.dataset.targetInput;
            const hiddenInput = document.getElementById(targetInputId);
            const placeholder = dropdown.dataset.placeholder;

            trigger.addEventListener('click', function (e) {
                e.stopPropagation();

                document.querySelectorAll('.dropdown-panel').forEach((otherPanel) => {
                    if (otherPanel !== panel) {
                        otherPanel.classList.add('hidden');
                    }
                });

                panel.classList.toggle('hidden');
            });

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const value = this.value.toLowerCase().trim();

                    optionLabels.forEach((label) => {
                        const text = label.innerText.toLowerCase();
                        label.style.display = text.includes(value) ? 'flex' : 'none';
                    });
                });
            }

            applyBtn.addEventListener('click', async function () {
                const checked = dropdown.querySelector('input[type="radio"]:checked');
                const oldValue = hiddenInput.value;

                if (checked) {
                    hiddenInput.value = checked.value;
                    selectedLabel.textContent = checked.dataset.label;
                } else {
                    hiddenInput.value = '';
                    selectedLabel.textContent = placeholder;
                }

                if (targetInputId === 'university_a_id' && oldValue !== hiddenInput.value) {
                    document.getElementById('program_a_id').value = '';
                }

                if (targetInputId === 'university_b_id' && oldValue !== hiddenInput.value) {
                    document.getElementById('program_b_id').value = '';
                }

                panel.classList.add('hidden');
                await updateSelectionState();
            });

            resetBtn.addEventListener('click', async function () {
                const checked = dropdown.querySelector('input[type="radio"]:checked');

                if (checked) {
                    checked.checked = false;
                }

                hiddenInput.value = '';
                selectedLabel.textContent = placeholder;

                if (searchInput) {
                    searchInput.value = '';
                }

                optionLabels.forEach((label) => {
                    label.style.display = 'flex';
                });

                if (targetInputId === 'university_a_id') {
                    document.getElementById('program_a_id').value = '';
                }

                if (targetInputId === 'university_b_id') {
                    document.getElementById('program_b_id').value = '';
                }

                if (targetInputId === 'program_a_id') {
                    document.getElementById('program_a_id').value = '';
                }

                if (targetInputId === 'program_b_id') {
                    document.getElementById('program_b_id').value = '';
                }

                panel.classList.add('hidden');
                await updateSelectionState();
            });
        });
    }

    async function updateSelectionState() {
        const endpoint = form.dataset.endpoint;
        const params = new URLSearchParams(new FormData(form));

        try {
            const response = await fetch(`${endpoint}?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            document.getElementById('program-selectors-container').innerHTML = data.programFilters;
            document.getElementById('selected-program-cards-container').innerHTML = data.selectedCards;
            document.getElementById('comparison-section-inner').innerHTML = data.comparisonSection;

            comparedState = false;

            initComparisonDropdowns(document);
        } catch (error) {
            console.error('Selection AJAX error:', error);
        }
    }

    async function comparePrograms() {
        const universityA = document.getElementById('university_a_id').value;
        const universityB = document.getElementById('university_b_id').value;
        const programA = document.getElementById('program_a_id').value;
        const programB = document.getElementById('program_b_id').value;

        if (!universityA || !universityB) {
            alert('Please choose both universities first.');
            return;
        }

        if (!programA || !programB) {
            alert('Please choose both programs first.');
            return;
        }

        try {
            const response = await fetch(comparisonContainer.dataset.compareEndpoint, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    program_a_id: programA,
                    program_b_id: programB
                })
            });

            const data = await response.json();

            if (data.selectedCards) {
                document.getElementById('selected-program-cards-container').innerHTML = data.selectedCards;
            }

            document.getElementById('comparison-section-inner').innerHTML = data.comparisonSection;
            comparedState = true;
        } catch (error) {
            console.error('Compare AJAX error:', error);
        }
    }

    function exportComparison() {
        const universityA = document.getElementById('university_a_id').value;
        const universityB = document.getElementById('university_b_id').value;
        const programA = document.getElementById('program_a_id').value;
        const programB = document.getElementById('program_b_id').value;

        if (!universityA || !universityB) {
            alert('Please choose both universities first.');
            return;
        }

        if (!programA || !programB) {
            alert('Please choose both programs first.');
            return;
        }

        if (!comparedState) {
            alert('Please compare the programs before exporting the PDF.');
            return;
        }

        const exportUrl = new URL(comparisonContainer.dataset.exportEndpoint, window.location.origin);
        exportUrl.searchParams.set('program_a_id', programA);
        exportUrl.searchParams.set('program_b_id', programB);

        window.location.href = exportUrl.toString();
    }

    const aboutToggleBtn = document.getElementById('about-toggle-btn');
    const aboutContent = document.getElementById('about-content');

    if (aboutToggleBtn && aboutContent) {
        aboutToggleBtn.addEventListener('click', function () {
            aboutContent.classList.toggle('hidden');
            aboutToggleBtn.textContent = aboutContent.classList.contains('hidden') ? 'See More' : 'See Less';
        });
    }

    initComparisonDropdowns(document);

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.compare-dropdown')) {
            document.querySelectorAll('.dropdown-panel').forEach((panel) => {
                panel.classList.add('hidden');
            });
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('#compare-programs-btn')) {
            comparePrograms();
        }

        if (e.target.closest('#export-comparison-btn')) {
            exportComparison();
        }
    });
});