app.service("MunicipioService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.postTmMunicipio = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/TmMunicipio.php",
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
        
        this.putTmMunicipio = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/TmMunicipio.php",
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
        
        this.GetMunicipiosByDepartamentoId = function (DepartamentoId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmMunicipio.php?DepartamentoId=" + DepartamentoId);
            return req;
        };
    }]);



