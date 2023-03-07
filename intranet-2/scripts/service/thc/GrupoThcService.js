app.service("GrupoThcService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.getGrupos = function () {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/thc/Grupo.php?Grupos=True`);
            return req;
        };
        this.getUsuariosByGrupoId = function (GrupoId) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/thc/Grupo.php?GrupoId=${GrupoId}`);
            return req;
        };
        this.IsInEnfermeria = (UsuarioId) => {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/thc/Grupo.php?UsuarioId_enf=${UsuarioId}`);
            return req;
        };
    }]);


