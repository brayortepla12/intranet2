app.service("HojaVidaServiceBiomedico",[ "$http", function ($http) {
   this.postHojaVida = function (obj) {
        var req = $http({
            method: 'POST',
            url: "/Biomedico/api/HojaVida.php",
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
    this.putHojaVida = function (obj) {
        var req = $http({
            method: 'PUT',
            url: "/Biomedico/api/HojaVida.php",
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
    this.GetHojaVida = function (ServicioId) {
        var req = $http({
            method: 'GET',
            url: "/Biomedico/api/HojaVida.php?ServicioId=" + ServicioId
        });
        return req;
    };
    
    this.GetHojaVidaExterno = function (ServicioId) {
        var req = $http({
            method: 'GET',
            url: "/Biomedico/api/HojaVida.php?ServicioExt=" + ServicioId
        });
        return req;
    };
    
    
    this.GetHojaVidaSedeId = function (SedeId, ServicioId, Estado, TipoEquipo) {
        var req = $http({
            method: 'GET',
            url: "/Biomedico/api/HojaVida.php?SedeId=" + SedeId + "&ServicioId_all=" + ServicioId + "&Estado=" + Estado + "&TipoEquipo=" + TipoEquipo
        });
        return req;
    };
    this.GetHojaVidaServicioId = function (ServicioId) {
        var req = $http({
            method: 'GET',
            url: "/Biomedico/api/HojaVida.php?ServicioId=" + ServicioId
        });
        return req;
    };
    this.GetContratosPorVencer = function () {
        var req = $http({
            method: 'GET',
            url: "/Biomedico/api/HojaVida.php?ContratosPorVencer=True"
        });
        return req;
    };
    this.GetAnexosByHojaVidaId = function (HojaVidaId) {
        var req = $http({
            method: 'GET',
            url: "/Biomedico/api/HojaVida.php?Anexos_HojaVidaId=" + HojaVidaId
        });
        return req;
    };
    this.GetHojaVidaHojaVidaId = function (HojaVidaId) {
        var req = $http({
            method: 'GET',
            url: "/Biomedico/api/HojaVida.php?HojaVidaId=" + HojaVidaId
        });
        return req;
    };
    this.CountHojaVidas = function (UsuarioId) {
        var req = $http({
            method: 'GET',
            url: "/Biomedico/api/HojaVida.php?Cuenta=" + 1 + "&UsuarioId=" + UsuarioId // para acceder
        });
        return req;
    };
    this.GetNHojaVida = function () {
        var req = $http({
            method: 'GET',
            url: "/Biomedico/api/HojaVida.php"
        });
        return req;
    };
    this.DeleteHojaVida = function (obj) {
        var req = $http({
            method: 'DELETE',
            url: "/Biomedico/api/HojaVida.php",
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


