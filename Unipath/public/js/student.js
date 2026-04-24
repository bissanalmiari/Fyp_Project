
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

    /* ───── HEART TOGGLE ───── */
    window.toggleHeart = function(btn) {
        const card = btn.closest('.program-card');
        const liked = btn.classList.toggle('liked');

        if (!liked) {
            card.remove();
        }

        updateCount();
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
            const target = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = target;
            }, 400);
        });
    });

});
