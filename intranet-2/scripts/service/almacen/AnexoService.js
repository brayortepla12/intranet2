app.service("AnexoService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    
    
    this.GetByVerificadorIdId = function (VerificadorId, FlujoTrabajoId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Anexo.php?VerificadorId=" + VerificadorId + "&FlujoTrabajoId=" + FlujoTrabajoId);
        return req;
    };
}]);




