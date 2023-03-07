app.service("MantenimientoPreventivoSistemaService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
   this.postHojaVida = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
            method: 'POST',
            url: "/Polivalente/api/MantenimientoPreventivoSistema.php",
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
        cfpLoadingBar.start();
        var req = $http({
            method: 'GET',
            url: "/Polivalente/api/MantenimientoPreventivoSistema.php?UsuarioId=" + UsuarioId 
        });
        return req;
    };
    this.GetNEquiposByServicio = function (UsuarioId) {
        cfpLoadingBar.start();
        var req = $http({
            method: 'GET',
            url: "/Polivalente/api/MantenimientoPreventivoSistema.php?Cuenta=" + 10 + "&UsuarioId=" + UsuarioId // cualquier numero
        });
        return req;
    };
    
    this.GetAllByServicios = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
            method: 'POST',
            url: "/Polivalente/api/MantenimientoPreventivoSistema.php",
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
    this.GetAllBySede = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
            method: 'POST',
            url: "/Polivalente/api/MantenimientoPreventivoSistema.php",
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

