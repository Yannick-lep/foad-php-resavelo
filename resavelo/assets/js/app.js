document.addEventListener("DOMContentLoaded", () => {

    /* ===== Effet hover sur les cards ===== */
    const cards = document.querySelectorAll(".card");

    cards.forEach(card => {
        card.addEventListener("mouseenter", () => {
            card.style.transform = "translateY(-6px)";
            card.style.boxShadow = "0 10px 20px rgba(0,0,0,0.15)";
            card.style.transition = "0.2s ease";
        });

        card.addEventListener("mouseleave", () => {
            card.style.transform = "translateY(0)";
            card.style.boxShadow = "none";
        });
    });

    /* ===== Recherche instantanée ===== */
    const searchInput = document.getElementById("searchBike");

    if (searchInput) {
        searchInput.addEventListener("keyup", () => {
            const value = searchInput.value.toLowerCase();
            document.querySelectorAll(".card").forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(value) ? "block" : "none";
            });
        });
    }

    /* ===== Confirmation suppression ===== */
    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.addEventListener("click", e => {
            if (!confirm("Es-tu sûr de vouloir supprimer ?")) {
                e.preventDefault();
            }
        });
    });

});
