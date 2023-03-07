app.service("TipoMedicamentoService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.getTiposMedicamentos = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TipoMedicamento.php");
            return req;
        };
    }]);


