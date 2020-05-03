export default class Loader {

    constructor(loaderId = "single-loader") {
        this.loaderId = loaderId;
    }

    generateText(text = 'Chargement...') {
        return `<span class="loader-text">${text}</span>`
    }

    get() {
        this.remove();
        return `
        <div id="${this.loaderId}" class="d-flex justify-content-center mt-4 mb-4">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        `;
    }

    remove() {
        let $loader = $(`#${this.loaderId}`);
        $loader.remove();

    }


}