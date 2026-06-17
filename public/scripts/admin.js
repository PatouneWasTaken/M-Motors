document.addEventListener("DOMContentLoaded", () => {

    const BASE = "/M-Motors/public/index.php";

    const addForm     = document.querySelector("#addVehicleForm");
    const filtersForm = document.querySelector("#filters");
    const container   = document.querySelector("#admin-vehicles-container");
    const message     = document.querySelector("#form-message");

    if (!container) return;

    let timeout = null;

    // Animation d'apparition des cartes
    const animateCards = () => {
        document.querySelectorAll("#admin-vehicles-container .card")
            .forEach((card, i) => setTimeout(() => card.classList.add("show"), i * 100));
    };

    // Charge / rafraîchit la liste des véhicules (en tenant compte des filtres)
    const loadVehicles = () => {
        let url = `${BASE}?page=admin_vehicles`;

        if (filtersForm) {
            const params = new URLSearchParams(new FormData(filtersForm));
            url += "&" + params.toString();
        }

        container.classList.add("fade-out");

        fetch(url)
            .then(res => res.text())
            .then(html => {
                container.innerHTML = html;
                container.classList.remove("fade-out");
                animateCards();
            })
            .catch(err => {
                console.error(err);
                container.innerHTML = "<p>Erreur de chargement.</p>";
                container.classList.remove("fade-out");
            });
    };

    // Anti-spam partagé pour les filtres
    const scheduleLoad = () => {
        clearTimeout(timeout);
        timeout = setTimeout(loadVehicles, 300);
    };

    if (filtersForm) {
        filtersForm.addEventListener("input", scheduleLoad);
        filtersForm.addEventListener("change", scheduleLoad);
    }

    // Ajout d'un véhicule (AJAX)
    if (addForm) {
        addForm.addEventListener("submit", (e) => {
            e.preventDefault();

            const formData = new FormData(addForm);
            if (message) message.textContent = "Ajout en cours...";

            fetch(`${BASE}?page=admin_add_vehicles`, {
                method: "POST",
                body: formData
            })
            .then(res => res.text())
            .then(response => {
                if (response.trim() === "OK") {
                    if (message) message.textContent = "Véhicule ajouté !";
                    addForm.reset();
                    const preview = document.querySelector("#preview");
                    if (preview) preview.style.display = "none";
                    loadVehicles();
                } else {
                    if (message) message.textContent = response || "Erreur";
                }
            })
            .catch(err => {
                console.error(err);
                if (message) message.textContent = "Erreur réseau";
            });
        });
    }

    // Chargement initial
    loadVehicles();
});
