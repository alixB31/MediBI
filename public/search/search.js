document.addEventListener("DOMContentLoaded", function () {
    // Fonction d'initialisation de Choices.js
    function initChoices() {
        document.querySelectorAll(".multi-select").forEach(select => {
            if (!select.dataset.choicesInitialized) {
                new Choices(select, {
                    removeItemButton: true,
                    allowHTML: true,
                    placeholder: true,
                    placeholderValue: "Sélectionner...",
                    searchEnabled: true,
                });
                select.dataset.choicesInitialized = "true";
            }
        });
    }

    // Initialisation sur les sélecteurs existants dès le chargement
    initChoices();
});
