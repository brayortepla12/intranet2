app.service("MaternaService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.postTmMaterna = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/TmMaterna.php",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transformRequest: function (obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: obj
            });
            return req;
        };
        
        this.putTmMaterna = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/TmMaterna.php",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transformRequest: function (obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: obj
            });
            return req;
        };
        
        this.GetMaternaByDocumento = function (Documento) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmMaterna.php?Documento=" + Documento);
            return req;
        };
        
        this.getMaternaByMaternaId = function (MaternaId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmMaterna.php?MaternaId=" + MaternaId);
            return req;
        };
        
        this.getMaternas = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmMaterna.php");
            return req;
        };
        
        this.getAgendaMaterna = function (LiderId, From, To) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmMaterna.php?LiderId=" + LiderId  + "&From=" + From + "&To=" + To);
            return req;
        };
        
        this.GetActividadMes = function (Year, Mes, MunicipioId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmMaterna.php?Year=" + Year  + "&Mes=" + Mes + "&MunicipioId=" + MunicipioId);
            return req;
        };
        
        this.GetMaternaRegistradasByMes = function (Year, Mes, MunicipioId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmMaterna.php?Year_reg=" + Year  + "&Mes_reg=" + Mes + "&MunicipioId_reg=" + MunicipioId);
            return req;
        };
        
        
        
    }]);



