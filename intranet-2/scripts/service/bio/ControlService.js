app.service("ControlService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.getListEmpleado = function (Dispositivo) {
        cfpLoadingBar.start();
        var req = $http.get(`/Polivalente/api/Control.php?Dispositivo=${Dispositivo}`);
        return req;
    };
    
    this.getUltimoControl = function (PersonaId) {
        cfpLoadingBar.start();
        var req = $http.get(`/Polivalente/api/Control.php?PersonaId=${PersonaId}`);
        return req;
    };
    
    this.getBiometricoByPersonaId = function (PersonaId, Desde, Hasta) {
        cfpLoadingBar.start();
        var req = $http.get(`/Polivalente/api/Control.php?PersonaId_c=${PersonaId}&Desde_c=${Desde}&Hasta_c=${Hasta}`);
        return req;
    };
}]);

