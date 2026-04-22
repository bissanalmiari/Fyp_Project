document.addEventListener('DOMContentLoaded', () => {
    const popup = document.getElementById('success-popup');
    const closeBtn = document.getElementById('close-popup');

    if(popup) {
        // Auto hide after 3 seconds
        setTimeout(() => {
            popup.style.display = 'none';
        }, 3000);

        // Close on button click
        closeBtn.addEventListener('click', () => {
            popup.style.display = 'none';
        });
    }
    
});
function toggleHeart(btn) {
    const card = btn.closest('.program-card'); // get the card
    const liked = btn.classList.toggle('liked'); // toggle heart

    if (!liked) {
        // remove card from grid if unliked
        card.remove();
    }

    updateCount();
}

function filterCards() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.program-card');
    cards.forEach(card => {
        const name = (card.dataset.name || '').toLowerCase();
        const show = name.includes(q);
        card.style.display = show ? '' : 'none';
    });

    updateCount();
}

function updateCount() {
    const cards = document.querySelectorAll('.program-card');
    const visible = Array.from(cards).filter(card => card.style.display !== 'none').length;
    const count = document.getElementById('resultsCount');
    count.innerHTML = `<span>${visible}</span> program${visible !== 1 ? 's' : ''} saved`;
}

// Initialize count on page load
updateCount();

 function toggleQuiz(id) {
    document.getElementById(id).classList.toggle('open');
  }
 
  /* Animate bars on load */
  window.addEventListener('load', () => {
    document.querySelectorAll('.compatibility-bar').forEach(bar => {
      const target = bar.style.width;
      bar.style.width = '0%';
      setTimeout(() => { bar.style.width = target; }, 400);
    });
  });