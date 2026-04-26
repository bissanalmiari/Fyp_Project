
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