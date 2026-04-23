document.addEventListener("DOMContentLoaded", () => {

    const form = document.querySelector("#filters");
    const container = document.querySelector("#vehicles-container");

    if (!form || !container) return;

    let timeout = null;

	const animateCards = () => {
    	const cards = document.querySelectorAll("#vehicle-container .card");

    	cards.forEach((card, index) => {
        	setTimeout(() => {
            	card.classList.add("show");
        	}, index * 100); // décalage entre chaque carte
    	});
	};

    const fetchVehicles = () => {

        const formData = new FormData(form);
        const params = new URLSearchParams(formData);

        //fade out
        container.classList.add("fade-out");

        setTimeout(() => {

            fetch("/index.php?page=vehicles&" + params.toString())
    			.then(response => response.text())
    			.then(html => {

        			container.innerHTML = html;

        			//lancer animation après injection
					container.classList.remove("fade-out");
        			animateCards();
    			})
                .catch(err => {
                    console.error(err);
                    container.innerHTML = "<p>Erreur de chargement.</p>";
                    container.classList.remove("fade-out");
                });

        }, 300);
    };

	fetchVehicles();

    //no spam
    form.addEventListener("input", () => {
        clearTimeout(timeout);
        timeout = setTimeout(fetchVehicles, 300);
    });

    form.addEventListener("change", fetchVehicles);

});