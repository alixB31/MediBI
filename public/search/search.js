document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".multi-select").forEach(select => {
        new Choices(select, {
            removeItemButton: true,
            allowHTML: true,
            placeholder: true,
            placeholderValue: "Sélectionner...",
            searchEnabled: true,
        });
    });

    // Ajout dynamique des filtres
    document.querySelectorAll(".add-filter").forEach(button => {
        button.addEventListener("click", function () {
            const field = this.dataset.field;
            const filterGroup = document.getElementById(`${field}_filters`);

            const options = document.querySelector(`#${field}_filters .multi-select`).innerHTML;

            const filterDiv = document.createElement("div");
            filterDiv.classList.add("filter-option");
            filterDiv.innerHTML = `
                <select name="${field}_filter_value[]" class="multi-select" multiple>
                    ${options}
                </select>
                <button type="button" class="remove-filter" data-field="${field}">×</button>
            `;

            filterGroup.appendChild(filterDiv);

            new Choices(filterDiv.querySelector(".multi-select"), {
                removeItemButton: true,
                allowHTML: true,
                placeholder: true,
                placeholderValue: "Sélectionner...",
                searchEnabled: true,
            });
        });
    });

    // Suppression des filtres (évite la suppression du dernier)
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
