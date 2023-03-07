app.service("DepartamentoService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.postTmDepartamento = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/TmDepartamento.php",
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
        
        this.putTmDepartamento = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/TmDepartamento.php",
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
        
        this.GetDepartamentos = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmDepartamento.php");
            return req;
        };
    }]);



