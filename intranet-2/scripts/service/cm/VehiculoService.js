app.service("VehiculoService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.getVehiculos = function () {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Vehiculo.php");
        return req;
    };
    this.getAllVehiculos = function () {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Vehiculo.php?ALL=True");
        return req;
    };
    this.postDM = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/Vehiculo.php",
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
