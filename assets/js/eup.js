import Request from "./Core/Request";

$(document).ready(async () => {
    const request = new Request();
    const $loadingElement = $("#loading-js");

    if ($loadingElement !== undefined && $loadingElement.length > 0) {

        const response = await request.sendGet('api.eup.long_polling', {key: $loadingElement.data('key')});

        if (response.status === 200) {

        } else {
            $loadingElement.html(`<div class="alert alert-danger">
Oups une erreur est survenue... Le fichier est inexistant ou bien traitement a engendré une erreur.
</div>`)
        }
    }


});