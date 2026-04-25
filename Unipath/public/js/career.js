

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

$(document).ready(function () {

   
    // LOAD CAREERS WITH AJAX
   
    function loadCareers() {
        $.ajax({
            url: "/careers",
            method: "GET",
            data: {
                search: $('#search').val(),
                category: $('#category').val()
            },
            success: function (response) {
                $('#careers-table').html(response);
                  expanded = false; 
                  handleShowMore();
            },
            error: function (xhr) {
                console.error("Error loading careers:", xhr.responseText);
            }
        });
    }

  
    $('#search').on('keyup', function () {
        loadCareers();
    });

    $('#category').on('change', function () {
        loadCareers();
    });

    let expanded = false;
    function handleShowMore() {
        const cards = document.querySelectorAll('.career-card');
        const btn = document.getElementById('toggleBtn');

        if (!btn) return;

        function updateView() {
            cards.forEach((card, index) => {
                if (!expanded && index >= 12) {
                    card.style.display = "none";
                } else {
                    card.style.display = "";
                }
            });

            btn.textContent = expanded ? "Show Less" : "Show More";
        }

        // Hide button if less than 20
        if (cards.length <= 20) {
            btn.style.display = "none";
            return;
        } else {
            btn.style.display = "block";
        }

         btn.onclick = null;

        const newBtn = document.getElementById('toggleBtn');

        newBtn.addEventListener('click', function () {
            expanded = !expanded;
            updateView();
        });

        updateView(); 
    }


    handleShowMore();

});