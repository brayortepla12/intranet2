app.service("CargoService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.getCargoById = function (CargoId) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/Persona.php?CargoId=${CargoId}`);
            return req;
        };
        this.getCargos = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?Cargo=True");
            return req;
        };
        
        this.postCargo = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/Persona.php",
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
        this.putCargo = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/Persona.php",
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
    }]);
