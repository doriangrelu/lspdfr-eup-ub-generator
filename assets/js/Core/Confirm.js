import Modal from "./Modal";
import Utility from "./Utility";

let forceSend = false;
export default class Confirm {


    constructor(elementsForm = [], elementsLink = []) {
        this.elementsForm = elementsForm;
        this.elementsLink = elementsLink;
    }


    async confirmFormAction(event) {
        if (Confirm.getForceSend()) {
            return true;
        }
        event.preventDefault();
        let $form = $(this);
        let modal = new Modal();
        let utility = new Utility();
        let dataMessage = $(this).data('message');
        let message = dataMessage === undefined ? "Confirmez-vous cette action ?" : utility.htmlEntities(dataMessage);
        message = `
        <p class="text-center text-info mt-2 mb-2">
            <i class="fas fa-info-circle"></i> ${message}
        </p>
        <hr>
        <div class="text-center">
            <button id="rollback-js" class="btn btn-outline-danger">Annuler</button>
            <button id="commit-js" class="btn btn-outline-success">Confirmer</button>
        </div>
        `;

        modal.updateContent('Confirmation', message);
        modal.show('default', true);
        let promise = new Promise(function (resolve, reject) {
            $("body").on("click", "#rollback-js", reject);
            $("body").on("click", "#commit-js", resolve);
        });

        let response = await promise.then(() => {
            modal.isBodyLoading();
            Confirm.setForceSend();
            $form.unbind('submit').submit();
            return true;
        }).catch(() => {
            modal.hide();
            return false;
        });


    }

    static setForceSend() {
        forceSend = true;
    }

    static getForceSend() {
        return forceSend;
    }

    confirmLinkAction(event) {
        event.preventDefault();
        let modal = new Modal();
        let utility = new Utility();
        let dataMessage = $(this).data('message');
        let message = dataMessage === undefined ? "Confirmez-vous cette action ?" : utility.htmlEntities(dataMessage);
        message = `
        <p class="text-center text-info mt-2 mb-2">
            <i class="fas fa-info-circle"></i> ${message}
        </p>
        <hr>
        <div class="text-center">
            <button id="rollback-js" class="btn btn-outline-danger">Annuler</button>
            <button id="commit-js" class="btn btn-outline-success">Confirmer</button>
        </div>
        `;
        modal.updateContent('Confirmation', message);
        modal.show('default', true);
        let promise = new Promise(function (resolve, reject) {
            $("body").on("click", "#rollback-js", reject);
            $("body").on("click", "#commit-js", resolve);
        });
        promise.then(() => {
            window.location = $(this).attr("href");
            modal.isBodyLoading();
        }).catch(() => {
            modal.hide();
        });
    }


    handle() {
        if (this.elementsForm.length > 0) {
            $("body").on("submit", this.elementsForm.join(', '), this.confirmFormAction);
        }
        if (this.elementsLink.length > 0) {
            $("body").on("click", this.elementsLink.join(', '), this.confirmLinkAction);
        }
    }


}