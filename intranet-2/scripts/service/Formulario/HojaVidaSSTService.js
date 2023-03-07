app.service("HojaVidaSSTService", ["$http", function ($http) {
        this.postHojaVida = function (obj) {
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/HojaVidaSST.php",
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
                url: "/Polivalente/api/HojaVidaSST.php",
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
                url: "/Polivalente/api/HojaVidaSST.php?ServicioId=" + ServicioId
            });
            return req;
        };
        this.GetHojaVidaByServicio = function (ServicioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVidaSST.php?ServicioId_print=" + ServicioId
            });
            return req;
        };
        this.GetHojaVidaSedeId = function (SedeId, ServicioId, Estado) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVidaSST.php?SedeId=" + SedeId + "&ServicioId_all=" + ServicioId + "&Estado=" + Estado
            });
            return req;
        };
        this.GetHojaVidaSedeId_Cronograma = function (SedeId, ServicioId, Estado) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVidaSST.php?SedeId2=" + SedeId + "&ServicioId_all_2=" + ServicioId + "&Estado_2=" + Estado
            });
            return req;
        };
        this.GenerarExcel = function (SedeId, ServicioId, Estado) {
            window.open("/Polivalente/api/HojaVidaSST.php?SedeId_Excel=" + SedeId + "&ServicioId_all_Excel=" + ServicioId + "&Estado_Excel=" + Estado);
        };
        
        this.GetHojaVidaServicioId = function (ServicioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVidaSST.php?ServicioId=" + ServicioId
            });
            return req;
        };
        this.GetHojaVidaHojaVidaId = function (HojaVidaId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVidaSST.php?HojaVidaId=" + HojaVidaId
            });
            return req;
        };
        this.CountHojaVidas = function (UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVidaSST.php?Cuenta=" + 1 + "&UsuarioId=" + UsuarioId // para acceder
            });
            return req;
        };
        this.CountComputadores = function (UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVidaSST.php?Cuenta=" + 2 + "&UsuarioId=" + UsuarioId // para acceder
            });
            return req;
        };
        this.CountImpresoras = function (UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVidaSST.php?Cuenta=" + 3 + "&UsuarioId=" + UsuarioId // para acceder
            });
            return req;
        };
        this.GetNHojaVida = function () {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/HojaVidaSST.php"
            });
            return req;
        };
        this.DeleteHojaVida = function (obj) {
            var req = $http({
                method: 'DELETE',
                url: "/Polivalente/api/HojaVidaSST.php",
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


