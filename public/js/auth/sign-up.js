import { CoreJs } from "../core.js";

let form = $('#sign-up-form');

form.on('submit', function(event) {
    event.preventDefault(); // Empêche la soumission par défaut du formulaire

    // Récupère les valeurs des champs du formulaire
    let data = form.serializeArray();

    let request = CoreJs.ajax('POST', '/user/create-user', data);


    request
        .done(function(response) {
            // $('#sign-up-result').text('Inscription réussie !');
            $('#sign-up-result').text(response.success_message);
            // Redirection ou autre traitement ici si nécessaire
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            // Échec
            if (jqXHR.status === 401) {
                $('#sign-up-result').text(jqXHR.responseJSON['error_message']);
            } else {
                // Gestion d'autres erreurs HTTP non gérées spécifiquement
                // Exemple : Afficher un message générique
                $('#sign-up-result').text('Une erreur s\'est produite. Veuillez réessayer plus tard.');
            }
        })
    ;
});