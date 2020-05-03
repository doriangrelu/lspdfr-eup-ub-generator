import Loader from "./Loader";
import "bootstrap";
import ReactDOM from 'react-dom';


export default class Modal {

    constructor() {
        this.$modal = $("#single-modal-js");
        this.$title = this.$modal.find(".modal-title")
        this.$body = this.$modal.find(".modal-body")
        this.$modal.on("hidden.bs.modal", () => {
            this.clear();
        });
        this.optionsSize = {
            small: 'modal-sm',
            default: null,
            large: 'modal-lg',
            extraLarge: 'modal-xl'
        };

    }

    updateContent(title, htmlContent, show = false) {
        this.$title.html(title);
        this.$body.html(htmlContent);
        if (show) {
            this.show();
        }
    }

    clear() {
        this.$modal.removeData('backdrop');
        let $modalDialog = this.$modal.find('.modal-dialog');
        for (let i in this.optionsSize) {
            let classname = this.optionsSize[i];
            if (classname !== null) {
                $modalDialog.removeClass(classname);
            }
        }
        this.updateContent('', '');
    }


    hide() {
        this.$modal.modal('hide')
    }


    setContentAsError(title, contentMessage) {
        if (contentMessage.toString().trim() === '') {
            contentMessage = 'Aucun détail... Si cette erreur persiste, merci de contacter un administrateur.';
        }

        this.updateContent('Oups - Une erreur est survenue !', `
        <div class="alert alert-danger" role="alert">
          <h4 class="alert-heading">${title}</h4>
          <p><i class="fas fa-exclamation-triangle"></i> ${contentMessage}</p>        
        </div>
        `)
    }


    contentAsReactComponent(component, title = "Composant REACT") {
        this.updateContent(title, `<div id="modal-react-component-js"></div>`);
        ReactDOM.render(component, document.getElementById("modal-react-component-js"));
    }


    show(size = 'default', isStatic = false) {
        if (isStatic) {
            this.$modal.data('backdrop', 'static')
        }

        let classname = this.optionsSize[size];
        if (classname !== null && classname !== undefined) {
            let $modalDialog = this.$modal.find('.modal-dialog');
            $modalDialog.addClass(classname);
        }

        console.log(this.$modal.length);
        this.$modal.modal('show');
    }


    isBodyLoading() {
        let loader = new Loader("modal-loader");
        this.updateContent('Chargement...', loader.get());
    }

}