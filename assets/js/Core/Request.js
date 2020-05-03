export default class Request {


    constructor() {
        this.directoryUrl = $('#directory-url-js').val();
    }

    async build(routeName, routeParameters, httpMethod, body, queryParams) {
        let directoryContent = await fetch(this.directoryUrl);
        let response = await directoryContent.json();
        for (let i in response) {
            let infos = response[i];
            if (routeName === infos.route) {
                let requestInformations = this.createRequestInformations(infos, routeParameters, httpMethod, queryParams);
                let fetchOptions = {method: requestInformations.method};
                if (Object.keys(body).length > 0) {
                    fetchOptions.body = JSON.stringify(body);
                    fetchOptions.headers = new Headers({
                        'Content-Type': 'application/json',
                    });
                }
                let response = await fetch(requestInformations.url, fetchOptions);
                return response.json();
            }
        }
        throw "Oups une erreur est survenue, la route " + routeName + " est inconnue dans le registre de service."
    }


    createRequestInformations(routeObject, routeParameters, httpMethod, queryParams) {
        let index;
        let url = routeObject.url;
        let allowedMethods = routeObject.methods;
        for (index in routeParameters) {
            let value = routeParameters[index];
            let replacement = ':' + index + ':';
            url = url.replace(replacement, value);
        }
        let isAllowedMethod = allowedMethods.lastIndexOf(httpMethod.toLowerCase()) !== -1 || allowedMethods.lastIndexOf(httpMethod.toUpperCase()) !== -1;

        if (!isAllowedMethod) {
            throw "Not allowed method " + httpMethod + " for route " + routeObject.url;
        }

        let isTrim = Object.keys(queryParams).length;

        if (isTrim > 0) {
            url = url.trim() + '?';
        }
        for (let index in queryParams) {
            let value = queryParams[index];
            url = url + index + "=" + value + "&";
        }
        if (isTrim) {
            url = url.substr(0, url.length - 1);
        }
        return {
            url: url,
            method: httpMethod
        };
    }


    async sendPost(routeName, routeParameters = {}, body = {}, queryParams = {}) {
        return await this.build(routeName, routeParameters, 'POST', body, queryParams);
    }


    async sendPut(routeName, routeParameters = {}, body = {}, queryParams = {}) {
        return await this.build(routeName, routeParameters, 'PUT', body, queryParams);
    }


    async sendDelete(routeName, routeParameters = {}, body = {}, queryParams = {}) {
        return await this.build(routeName, routeParameters, 'DELETE', body, queryParams);
    }


    async sendGet(routeName, routeParameters = {}, body = {}, queryParams = {}) {
        return await this.build(routeName, routeParameters, 'GET', body, queryParams);
    }


}