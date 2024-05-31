import { CoreJs } from "../core.js";

let form = $('#sign-up-form');

form.on('submit', function(event) {
    event.preventDefault(); // Empêche la soumission par défaut du formulaire

    // Récupère les valeurs des champs du formulaire
    let data = form.serializeArray();
    let request = CoreJs.ajax('POST', '/user/create-user', data);

    request
        .done(function(response) {
            CoreJs.toast('success', response);
        })
        .fail(function(jqXHR) {
            if(jqXHR.status === 500)
                $('#sign-up-result').html(jqXHR.responseText);
            else
                CoreJs.toastError(JSON.parse(jqXHR.responseText));
        })
    ;
});