app.service("HojaVidaAmbulanciaService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.postHojaVida = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/HojaVidaAmbulancia.php",
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
        this.putHojaVida = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/HojaVidaAmbulancia.php",
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
        this.GetHojaVida = function (ServicioId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVidaAmbulancia.php?ServicioId=" + ServicioId
            });
            return req;
        };
        this.GetHojaVidaSedeId = function (SedeId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVidaAmbulancia.php?SedeId=" + SedeId
            });
            return req;
        };
        this.GetHojaVidaHojaVidaId = function (HojaVidaId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVidaAmbulancia.php?HojaVidaId=" + HojaVidaId
            });
            return req;
        };
        this.CountHojaVidas = function (UsuarioId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVidaAmbulancia.php?Cuenta=" + 1 + "&UsuarioId=" + UsuarioId // para acceder
            });
            return req;
        };
        this.GetNHojaVida = function () {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVidaAmbulancia.php"
            });
            return req;
        };
        this.GetHojaVidaALL = function () {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVidaAmbulancia.php?HojaVidaALL=True"
            });
            return req;
        };
        this.DeleteHojaVida = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'DELETE',
                url: "/Polivalente/api/HojaVidaAmbulancia.php",
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


