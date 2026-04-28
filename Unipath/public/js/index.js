document.addEventListener("DOMContentLoaded", () => {
    // =========================
    // Stats Counter
    // =========================
    const statsSection = document.getElementById("stats-section");
    const counters = document.querySelectorAll(".counter");
    let started = false;

    if (statsSection && counters.length > 0) {
        const animateCounter = (counter) => {
            const target = +counter.getAttribute("data-target");
            let current = 0;
            const increment = Math.max(1, Math.ceil(target / 100));

            const updateCounter = () => {
                current += increment;

                if (current >= target) {
                    counter.textContent = target.toLocaleString();
                } else {
                    counter.textContent = current.toLocaleString();
                    requestAnimationFrame(updateCounter);
                }
            };

            updateCounter();
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting && !started) {
                    started = true;
                    counters.forEach((counter) => animateCounter(counter));
                }
            });
        }, { threshold: 0.35 });

        observer.observe(statsSection);
    }

    // =========================
    // Success Stories Slider
    // =========================
    const stories = document.querySelectorAll(".success-story");
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");

    let currentStory = 0;

    const showStory = (index) => {
        stories.forEach((story, i) => {
            story.classList.toggle("hidden", i !== index);
        });
    };

    if (stories.length > 0 && prevBtn && nextBtn) {
        nextBtn.addEventListener("click", () => {
            currentStory = (currentStory + 1) % stories.length;
            showStory(currentStory);
        });

        prevBtn.addEventListener("click", () => {
            currentStory = (currentStory - 1 + stories.length) % stories.length;
            showStory(currentStory);
        });
    }

});
