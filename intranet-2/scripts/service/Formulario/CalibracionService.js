app.service("CalibracionService",[ "$http", function ($http) {
   this.postHojaVida = function (obj) {
        var req = $http({
            method: 'POST',
            url: "/Polivalente/api/Calibracion.php",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
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
    this.GetAllAlertas = function (UsuarioId) {
        var req = $http({
            method: 'GET',
            url: "/Polivalente/api/Calibracion.php?UsuarioId=" + UsuarioId
        });
        return req;
    };
    this.GetNEquiposByServicio = function () {
        var req = $http({
            method: 'GET',
            url: "/Polivalente/api/Calibracion.php?Cuenta=" + 10 // cualquier numero
        });
        return req;
    };
    this.GetAllByServicios = function (obj) {
        var req = $http({
            method: 'POST',
            url: "/Polivalente/api/Calibracion.php",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
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

