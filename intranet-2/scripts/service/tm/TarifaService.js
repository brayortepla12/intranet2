app.service("TarifaService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.postTarifa = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/TmTarifa.php",
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
        
        this.putTarifa = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/TmTarifa.php",
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
        
        this.getTarifas = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmTarifa.php");
            return req;
        };
        
        this.getTarifaByTarifaId = function (TarifaId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmTarifa.php?TarifaId=" + TarifaId);
            return req;
        };
        
        this.getTarifaByMaterna = function (Documento) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmTarifa.php?Documento=" + Documento);
            return req;
        };
        
        this.getTarifaByMunicipio = function (MunicipioId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmTarifa.php?OrigenId=" + MunicipioId);
            return req;
        };
        this.getTarifas = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmTarifa.php?TarifasAdmin=True");
            return req;
        };
    }]);



