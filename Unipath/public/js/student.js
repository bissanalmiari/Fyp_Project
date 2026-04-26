
document.addEventListener('DOMContentLoaded', () => {

    
    /* ───── POPUP ───── */
    const popup = document.getElementById('success-popup');
    const closeBtn = document.getElementById('close-popup');

    if (popup) {
        setTimeout(() => {
            popup.style.display = 'none';
        }, 3000);

        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                popup.style.display = 'none';
            });
        }
    }

    /* ───── CATEGORY → SUBCATEGORY TOGGLE ───── */
    const categoryCheckboxes = document.querySelectorAll('input[name="interests[]"]');
    const subcategoryBoxes = document.querySelectorAll('.subcategory-box');

    function toggleSubcategories() {
        const selected = Array.from(categoryCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        subcategoryBoxes.forEach(box => {
            box.style.display = selected.includes(box.dataset.category)
                ? "block"
                : "none";
        });
    }

    categoryCheckboxes.forEach(cb => {
        cb.addEventListener('change', toggleSubcategories);
    });

    toggleSubcategories();

    document.querySelectorAll('.preference-picker').forEach(select => {
        const list = document.getElementById(select.dataset.target);
        const inputName = select.dataset.name;

        if (!list || !inputName) {
            return;
        }

        function selectedValues() {
            return Array.from(list.querySelectorAll('.preference-token'))
                .map(token => token.dataset.value);
        }

        function refreshOptions() {
            const selected = selectedValues();

            Array.from(select.options).forEach(option => {
                option.disabled = option.value !== '' && selected.includes(option.value);
            });
        }

        function addToken(value, label) {
            if (!value || selectedValues().includes(value)) {
                return;
            }

            const token = document.createElement('span');
            token.className = 'preference-token';
            token.dataset.value = value;

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.innerHTML = '&times;';
            removeButton.setAttribute('aria-label', `Remove ${label}`);

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = inputName;
            input.value = value;

            token.appendChild(document.createTextNode(label));
            token.appendChild(removeButton);
            token.appendChild(input);
            list.appendChild(token);
            refreshOptions();
        }

        select.addEventListener('change', () => {
            const option = select.selectedOptions[0];

            if (option && option.value) {
                addToken(option.value, option.textContent.trim());
            }

            select.value = '';
        });

        list.addEventListener('click', event => {
            const button = event.target.closest('.preference-token button');

            if (!button) {
                return;
            }

            button.closest('.preference-token')?.remove();
            refreshOptions();
        });

        refreshOptions();
    });

    /* ───── HEART TOGGLE ───── */
    window.toggleHeart = function(btn, programId) {

    fetch(`/favorites/${programId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const card = btn.closest('.program-card');
            card.remove();
            updateCount();
        }
    })
    .catch(err => console.error(err));
};


    /* ───── SEARCH FILTER ───── */
    window.filterCards = function() {
        const q = document.getElementById('searchInput')?.value.toLowerCase() || '';
        const cards = document.querySelectorAll('.program-card');

        cards.forEach(card => {
            const name = (card.dataset.name || '').toLowerCase();
            card.style.display = name.includes(q) ? '' : 'none';
        });

        updateCount();
    };

    /* ───── COUNT UPDATE ───── */
    function updateCount() {
        const cards = document.querySelectorAll('.program-card');
        const visible = Array.from(cards)
            .filter(card => card.style.display !== 'none').length;

        const count = document.getElementById('resultsCount');
        if (count) {
            count.innerHTML = `<span>${visible}</span> program${visible !== 1 ? 's' : ''} saved`;
        }
    }

    updateCount();

    /* ───── QUIZ TOGGLE ───── */
    window.toggleQuiz = function(id) {
        document.getElementById(id)?.classList.toggle('open');
    };

    /* ───── ANIMATION ───── */
   window.addEventListener('load', () => {
     document.querySelectorAll('.compatibility-bar').forEach(bar => {
        const target = getComputedStyle(bar).width;

        bar.style.width = '0px';

        setTimeout(() => {
            bar.style.width = target;
        }, 100);
      });
    });

    
});
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('preview-image');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
    }
}
document.addEventListener("DOMContentLoaded", function () {

    const openBtn = document.getElementById("menuToggle");
    const closeBtn = document.getElementById("closeSidebar");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");

    function openSidebar() {
        sidebar.classList.remove("-translate-x-full");
        overlay.classList.remove("hidden");
    }

    function closeSidebar() {
        sidebar.classList.add("-translate-x-full");
        overlay.classList.add("hidden");
    }

    openBtn.addEventListener("click", openSidebar);
    closeBtn.addEventListener("click", closeSidebar);
    overlay.addEventListener("click", closeSidebar);
});
