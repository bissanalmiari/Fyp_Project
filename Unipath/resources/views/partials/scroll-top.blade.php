<style>
    #scrollTopBtn {
        position: fixed;
        right: 1.5rem;
        bottom: 1.5rem;
        z-index: 50;
        display: flex;
        width: 3rem;
        height: 3rem;
        align-items: center;
        justify-content: center;
        border: 0;
        border-radius: 9999px;
        background: #7F64CE;
        color: #fff;
        box-shadow: 0 12px 28px rgba(127, 100, 206, 0.28);
        cursor: pointer;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }

    #scrollTopBtn:hover {
        transform: scale(1.1);
        box-shadow: 0 16px 34px rgba(127, 100, 206, 0.36);
    }

    #scrollTopBtn:focus {
        outline: 4px solid rgba(196, 152, 242, 0.4);
        outline-offset: 2px;
    }

    #scrollTopBtn.is-visible {
        opacity: 1;
        pointer-events: auto;
    }
</style>

<button
    id="scrollTopBtn"
    type="button"
    aria-label="Scroll to top"
>
    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M12 19V5"></path>
        <path d="M5 12l7-7 7 7"></path>
    </svg>
</button>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const scrollBtn = document.getElementById('scrollTopBtn');

        if (!scrollBtn) {
            return;
        }

        function toggleScrollButton() {
            if (window.scrollY > 300) {
                scrollBtn.classList.add('is-visible');
            } else {
                scrollBtn.classList.remove('is-visible');
            }
        }

        window.addEventListener('scroll', toggleScrollButton, { passive: true });
        toggleScrollButton();

        scrollBtn.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
</script>
