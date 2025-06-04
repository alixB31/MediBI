$(document).ready(function () {

    // Initialisation de DataTables sans la barre de recherche
    $('#resultsTable').DataTable({
        searching: false,
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [[5, 10, 20, 50], [5, 10, 20, 50]],
        language: {
            lengthMenu: "Afficher _MENU_ lignes par page",
            info: "Page _PAGE_ sur _PAGES_",
            infoEmpty: "Aucun résultat disponible",
            infoFiltered: "(filtré depuis _MAX_ lignes)",
            emptyTable: "Aucune donnée disponible dans le tableau",
            paginate: {
                previous: "Précédent",
                next: "Suivant"
            }
        }
    });


    function isScreenWideEnough() {
        return $(window).width() >= 1100;
    }

    // Ouvrir la carte de détails
    $('#resultsTable tbody').on('click', 'tr', function () {
        if (!isScreenWideEnough()) return;

        const id = $(this).data('id');
        if (!id) {
            console.warn('Aucune donnée d\'identifiant trouvée sur la ligne cliquée.');
            return;
        }

        $.ajax({
            url: '../details/details.php',
            method: 'GET',
            data: { id },
            success: function (data) {
                $('#detailsContent').html(data);
                $('#mainContainer').addClass('details-visible');
            },
            error: function () {
                $('#detailsContent').html('<p>Erreur lors du chargement des détails.</p>');
            }
        });
    });

    // Fermer la carte de détails en cliquant en dehors de la carte
    $(document).on('click', function (event) {
        if (!$(event.target).closest('#detailsPanel').length && !$(event.target).closest('tr').length) {
            $('#mainContainer').removeClass('details-visible');
        }
    });

    // Empêcher la propagation du clic
    $('#detailsPanel').on('click', function (event) {
        event.stopPropagation();
    });
    $('#resultsTable').on('click', 'tr', function (event) {
        event.stopPropagation();
    });

    // Fermer les détails si l'écran devient trop étroit
    $(window).on('resize', function () {
        if (!isScreenWideEnough()) {
            $('#mainContainer').removeClass('details-visible');
        }
    });
});
