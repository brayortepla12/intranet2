app.service("HojaVidaService", ["$http", function ($http) {
        this.postHojaVida = function (obj) {
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/HojaVida.php",
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
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/HojaVida.php",
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
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVida.php?ServicioId=" + ServicioId
            });
            return req;
        };
        this.GetHojaVidaByServicio = function (ServicioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVida.php?ServicioId_print=" + ServicioId
            });
            return req;
        };
        this.GetHojaVidaSedeId = function (SedeId, ServicioId, Estado) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVida.php?SedeId=" + SedeId + "&ServicioId_all=" + ServicioId + "&Estado=" + Estado
            });
            return req;
        };
        this.GetHojaVidaServicioId = function (ServicioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVida.php?ServicioId=" + ServicioId
            });
            return req;
        };
        this.GetHojaVidaServicioIdWithTA = function (ServicioId, TA) {
            var req = $http({
                method: 'GET',
                url: `/Polivalente/api/HojaVida.php?ServicioId_ta=${ServicioId}&TA=${TA}` 
            });
            return req;
        };
        this.GetHojaVidaHojaVidaId = function (HojaVidaId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVida.php?HojaVidaId=" + HojaVidaId
            });
            return req;
        };
        this.CountHojaVidas = function (UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVida.php?Cuenta=" + 1 + "&UsuarioId=" + UsuarioId // para acceder
            });
            return req;
        };
        this.GetNHojaVida = function () {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVida.php"
            });
            return req;
        };
        this.DeleteHojaVida = function (obj) {
            var req = $http({
                method: 'DELETE',
                url: "/Polivalente/api/HojaVida.php",
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
        this.GetAllHojas = function () {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVida.php?HojaVidaAll=avion"
            });
            return req;
        };
    }]);


