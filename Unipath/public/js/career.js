

function getCareerMatches() {
  const major = document.getElementById("majorInput").value.trim();
  const majorSpan = document.getElementById("majorValue");
  const container = document.getElementById("matchResults");

  majorSpan.textContent = major;

  container.querySelectorAll('.career-item, .error').forEach(el => el.remove());
  
  if (!major) return;

  fetch("/match-career", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ major })
  })
  .then(res => res.json())
  .then(data => {
    if (data.ai_error) {
      container.innerHTML += `<p style="color:red;">${data.ai_error}</p>`;
      return;
    }

    let apiCareers = Array.isArray(data.ai_careers) ? data.ai_careers : [];

    if (apiCareers.length === 0) {
      container.innerHTML += `<p class="results-placeholder">No matching careers found</p>`;
      return;
    }

    const colors = ["var(--primary)", "var(--secondary)", "var(--highlight)", "var(--title)"];

    container.innerHTML += apiCareers.map((c, i) => `
      <div class="career-item">
        <span class="career-dot" style="background: ${colors[i % colors.length]}"></span>
        <span>${c}</span>
      </div>
    `).join("");
  })
  .catch(err => {
    console.error(err);
    container.innerHTML += `<p style="color:red;">Error fetching careers</p>`;
  });
}

/* Animate salary bars on scroll */
const bars = document.querySelectorAll('.bar-fill');

const observer = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.style.width = e.target.dataset.w + '%';
      observer.unobserve(e.target);
    }
  });
}, { threshold: 0.3 });

bars.forEach(b => observer.observe(b));

