app.service("MantenimientoPreventivoService",[ "$http", function ($http) {
   this.postHojaVida = function (obj) {
        var req = $http({
            method: 'POST',
            url: "/Polivalente/api/MantenimientoPreventivo.php",
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
            url: "/Polivalente/api/MantenimientoPreventivo.php?UsuarioId=" + UsuarioId 
        });
        return req;
    };
    this.GetNEquiposByServicio = function (UsuarioId) {
        var req = $http({
            method: 'GET',
            url: "/Polivalente/api/MantenimientoPreventivo.php?Cuenta=" + 10 + "&UsuarioId=" + UsuarioId // cualquier numero
        });
        return req;
    };
    this.GetAllByServicios = function (obj) {
        var req = $http({
            method: 'POST',
            url: "/Polivalente/api/MantenimientoPreventivo.php",
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

