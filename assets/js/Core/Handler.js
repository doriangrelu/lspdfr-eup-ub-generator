import $ from 'jquery';
import Confirm from "./Confirm";
import 'select2';
import Password from "./Password";


export default class Handler {

    handleJquery() {
        $.expr[':'].regex = function (elem, index, match) {
            var matchParams = match[3].split(','),
                validLabels = /^(data|css):/,
                attr = {
                    method: matchParams[0].match(validLabels) ?
                        matchParams[0].split(':')[0] : 'attr',
                    property: matchParams.shift().replace(validLabels, '')
                },
                regexFlags = 'ig',
                regex = new RegExp(matchParams.join('').replace(/^\s+|\s+$/g, ''), regexFlags);
            return regex.test(jQuery(elem)[attr.method](attr.property));
        }
    }

    handleFormControls() {
        $('input[type="file"]').change(function (e) {
            const fileName = e.target.files[0].name;
            const $labelElement = $(this).closest('.custom-file').find('label');
            $labelElement.html(fileName);
        });
    }

    handlePassword() {

        const password = new Password();
        $("body").on("input", ".first-password-js", password.seeHelp);
        $("body").on("input", ".second-password-js", password.compare);


        $("button[type=submit]").on("click", function () {
            if (password.isContainPasswordField()) {
                password.handleRuleValidity();
                password.handleEqualsValidity();
            }
        })


    }

    handleDatatable() {
        $(".data-table").DataTable();
    }

    handleConfirm() {
        let confirm = new Confirm(["form.require-confirmation-js"], ["link.require-confirmation-js"]);
        confirm.handle();
    }

    handleDismissAlert() {
        $('[data-toggle="tooltip"]').tooltip();
        setInterval(function () {
            $(".dismiss-alert-js").empty();
        }, 5000);
    }

    handleCaptcha() {
        $("body").on("submit", "form", function (event) {
            const $captchaErrorElement = $("#captcha-error-js");
            const $recaptcha = $(this).find(".g-recaptcha");

            if ($captchaErrorElement.length > 0) {
                $captchaErrorElement.remove();
            }

            if ($recaptcha.length > 0) {
                if ($("#g-recaptcha-response").val().length === 0) {
                    $(".g-recaptcha").prepend(`<p id="captcha-error-js" class="text-danger">Veuillez valider le captcha</p>`)
                    event.stopPropagation();
                    return false;
                }
            }
        });
    }

    handleAll() {
        this.handleJquery();
        this.handlePassword();
        this.handleCaptcha();
        this.handleFormControls();
        this.handleDismissAlert();
        this.handleConfirm();
        $('select').select2();
        $('.dropdown-toggle').dropdown()
    }
}