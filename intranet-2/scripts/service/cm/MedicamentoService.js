app.service("MedicamentoService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.getMedicamentos = function (TipoMedicamentoId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Medicamento.php?TipoMedicamentoId=" + TipoMedicamentoId);
            return req;
        };
        this.getAllMedicamentosByTipoMedicamento = function (TipoMedicamento) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Medicamento.php?TipoMedicamento=" + TipoMedicamento);
            return req;
        };
        this.getMedicamentoById = function (MedicamentoId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Medicamento.php?MedicamentoId=" + MedicamentoId);
            return req;
        };
        this.getMedicamentosLoteados = function (TipoMedicamento) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Medicamento.php?TipoMedicamento_loteado=" + TipoMedicamento);
            return req;
        };
        
    }]);


