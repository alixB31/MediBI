$(document).ready(function () {
    // Initialisation de DataTables sans la barre de recherche
    $('#resultsTable').DataTable({
        searching: false  // Désactive la barre de recherche
    });

    // Ouvrir la carte de détails
    $('#resultsTable tbody').on('click', 'tr', function () {
        const id = $(this).data('id');
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
        // Vérifier si le clic est en dehors du panneau de détails et des éléments cliquables
        if (!$(event.target).closest('#detailsPanel').length && !$(event.target).closest('tr').length) {
            $('#mainContainer').removeClass('details-visible');
        }
    });

    // Empêcher la propagation du clic lorsque l'on clique sur le panneau de détails ou sur les éléments de la ligne
    $('#detailsPanel').on('click', function (event) {
        event.stopPropagation();
    });

    $('#resultsTable').on('click', 'tr', function (event) {
        event.stopPropagation();
    });
});
