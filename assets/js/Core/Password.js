export default class Password {


    constructor() {

    }

    seeHelp(event) {
        const $formGroupElement = $(this).closest(".form-group");
        const value = $(this).val();
        const password = new Password();
        if ($("#help-password-js").length === 0) {
            $formGroupElement.append(`<div id="help-password-js" class="pt-2 pb-2 font-weight-bold">

            </div>`)
        }
        const $passwordHelperElement = $("#help-password-js");
        $passwordHelperElement.empty();
        password.validRule($passwordHelperElement, /[A-Z]/.test(value), 'Au moins une majuscule')
        password.validRule($passwordHelperElement, /[a-z]/.test(value), 'Au moins une minuscule')
        password.validRule($passwordHelperElement, /[0-9]/.test(value), 'Au moins un chiffre')
        password.validRule($passwordHelperElement, /[$@$!%*?&]/.test(value), 'Au moins un caractère spécial ($ @ ! % * ? &)')
        password.validRule($passwordHelperElement, value.length >= 8 && value.length <= 30, 'Compris entre 8 et 30 caractères')


        if (password.isRuleRespected()) {
            $passwordHelperElement.remove();
        }
        password.handleRuleValidity();
        $(this).on("focusout", function () {
            $passwordHelperElement.remove();
        })


    }

    handleRuleValidity() {
        const firstPasswordElement = document.querySelector(".first-password-js");
        if (this.isRuleRespected()) {
            firstPasswordElement.setCustomValidity("");
        } else {
            firstPasswordElement.setCustomValidity("Le mot de passe ne respecte pas les règles de sécurité ");
        }
    }

    handleEqualsValidity() {
        const secondPasswordElement = document.querySelector(".second-password-js");
        if (this.isSamePasswords()) {
            secondPasswordElement.setCustomValidity("");
        } else {
            secondPasswordElement.setCustomValidity("Les mots de passe ne correspondent pas");
        }
    }

    isContainPasswordField() {
        const currentPasswordSize = $(".first-password-js").length;
        return currentPasswordSize > 0;
    }

    isRuleRespected() {
        const value = $(".first-password-js").val();
        return /[A-Z]/.test(value)
            && /[a-z]/.test(value)
            && /[0-9]/.test(value)
            && /[$@$!%*?&]/.test(value)
            && value.length >= 8 && value.length <= 30;
    }

    isSamePasswords() {
        const currentPassword = $(".first-password-js").val();
        const secondPassword = $(".second-password-js ").val();
        return currentPassword === secondPassword;
    }

    compare(event) {
        const $formGroupElement = $(this).closest(".form-group");
        const currentPassword = $(".first-password-js").val();
        const secondPassword = $(this).val();
        if ($("#different-password-js").length === 0) {
            $formGroupElement.append(`<div id="different-password-js" class="pt-2 pb-2 font-weight-bold">
            </div>`)
        }
        const $passwordHelperElement = $("#different-password-js");
        if (currentPassword === secondPassword) {
            $passwordHelperElement.remove();
        }
        const password = new Password();
        password.handleEqualsValidity();
    }


    validRule($container, condition, message) {
        if (condition) {
            $container.append(`<i class="fa fa-check-circle text-success"/>&nbsp; ${message}<br/>`);
        } else {
            $container.append(`<i class="fa fa-times-circle text-danger"/>&nbsp; ${message}<br/>`);
        }
    }

}