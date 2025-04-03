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

    // Ajout dynamique des filtres
    document.querySelectorAll(".add-filter").forEach(button => {
        button.addEventListener("click", function () {
            const field = this.dataset.field;
            const filterGroup = document.getElementById(`${field}_filters`);

            // Vérifier si c'est une liste déroulante ou un champ texte
            const originalSelect = document.querySelector(`#${field}_filters .multi-select`);
            const isDropdown = originalSelect !== null;

            // Création du nouvel élément de filtre
            const filterDiv = document.createElement("div");
            filterDiv.classList.add("filter-option");

            let filterContent = `
                <select name="${field}_filter_type[]">
                    <option value="include">Inclure</option>
                    <option value="exclude">Exclure</option>
                </select>
            `;

            if (isDropdown) {
                // Si c'est une liste déroulante, dupliquer le <select>
                const options = originalSelect.innerHTML;
                filterContent += `<select name="${field}_filter_value[]" class="multi-select" multiple>${options}</select>`;
            } else {
                // Sinon, c'est un champ texte
                filterContent += `<input type="text" name="${field}_filter_value[]" placeholder="Entrez une valeur...">`;
            }

            filterContent += `<button type="button" class="remove-filter" data-field="${field}">×</button>`;
            filterDiv.innerHTML = filterContent;

            filterGroup.appendChild(filterDiv);

            // Réinitialisation Choices.js si nécessaire
            if (isDropdown) {
                initChoices();
            }
        });
    });

    // Suppression des filtres
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("remove-filter")) {
            const field = event.target.dataset.field;
            const filterGroup = document.getElementById(`${field}_filters`);
            if (filterGroup.children.length > 1) {
                event.target.closest(".filter-option").remove();
            }
        }
    });
});
