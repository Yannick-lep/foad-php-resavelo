document.addEventListener("DOMContentLoaded", () => {
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
});

const searchInput = document.getElementById("searchBike");

if (searchInput) {
    searchInput.addEventListener("keyup", () => {
        const value = searchInput.value.toLowerCase();
        const cards = document.querySelectorAll(".card");

        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(value) ? "block" : "none";
        });
    });
}