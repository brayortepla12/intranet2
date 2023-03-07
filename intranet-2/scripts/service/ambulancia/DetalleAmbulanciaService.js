app.service("DetalleAmbulanciaService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.getall = function () {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/DetalleAmbulancia.php"
            });
            return req;
        };
    }]);




