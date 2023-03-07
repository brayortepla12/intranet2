app.service("HDService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.getHDById = function (HDId) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/hd/HD.php?HDId=${HDId}`);
            return req;
        };
        this.VerificarPaciente = function (NoAdmision, Distribucion, FechaAPreparar) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/hd/HD.php?NoAdmision_v=${NoAdmision}&Distribucion_v=${Distribucion}&FechaAPreparar_v=${FechaAPreparar}`);
            return req;
        };
        this.getHDByIdNoP = function (HDId) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/hd/HD.php?HDId_NoP=${HDId}`);
            return req;
        };
        this.GetEstadisticas = function (Empresa, Mes, Year) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/hd/HD.php?Empresa_estadisticas=${Empresa}&Mes_estadisticas=${Mes}&Year_estadisticas=${Year}`);
            return req;
        };
        this.GetEstadisticasDetalladas = function (Empresa, Dia, Mes, Year) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/hd/HD.php?Empresa_ed=${Empresa}&Dia_ed=${Dia}&Mes_ed=${Mes}&Year_ed=${Year}`);
            return req;
        };
        this.getHDs = function (Estado, Dia, Mes, Year) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/hd/HD.php?Estado=${Estado}&Dia=${Dia}&Mes=${Mes}&Year=${Year}`);
            return req;
        };
        this.getHDsByEmpresa = function (Estado, Dia, Mes, Year, UsuarioId) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/hd/HD.php?UsuarioId_emp=${UsuarioId}&Estado_emp=${Estado}&Dia_emp=${Dia}&Mes_emp=${Mes}&Year_emp=${Year}`);
            return req;
        };
        this.getHDsByUsuarioId = function (UsuarioId, Estado, Dia, Mes, Year) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/hd/HD.php?UsuarioId_serv=${UsuarioId}&Estado_serv=${Estado}&Dia_serv=${Dia}&Mes_serv=${Mes}&Year_serv=${Year}`);
            return req;
        };
        this.getVariables = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/hd/HD.php?Variables=TRUE");
            return req;
        };
        this.getEmpresas = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/hd/HD.php?Empresas=TRUE");
            return req;
        };
        this.getSectores = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/hd/HD.php?Sectoresmysql=TRUE");
            return req;
        };
        this.getDistribucion = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/hd/HD.php?Distribucion=TRUE");
            return req;
        };
        this.getCantidadAP = (Estado,Dia,Mes,Year) => {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/hd/HD.php?Estadoap=${Estado}&Diaap=${Dia}&Mesap=${Mes}&Yearap=${Year}`);
            return req;
        };
        this.postHD = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/hd/HD.php",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transformRequest: function (o) {
                    var str = [];
                    for (var p in o)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(o[p]));
                    return str.join("&");
                },
                data: obj
            });
            return req;
        };
        this.putHD = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/hd/HD.php",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transformRequest: function (o) {
                    var str = [];
                    for (var p in o)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(o[p]));
                    return str.join("&");
                },
                data: obj
            });
            return req;
        };
    }]);

