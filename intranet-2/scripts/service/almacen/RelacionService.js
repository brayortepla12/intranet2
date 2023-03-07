app.service("RelacionService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.PostRelacion = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/Relacion.php",
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
        this.PutRelacion = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/Relacion.php",
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
        this.getAllRelacion = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Relacion.php");
            return req;
        };
        this.getRelacionByGrupo = function (GrupoId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Relacion.php?GrupoId=" + GrupoId);
            return req;
        };
        this.getRelacionByUsuarioId = function (ServicioId, UsuarioId, Tipo) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Relacion.php?ServicioId=" + ServicioId + "&UsuarioId=" + UsuarioId + "&Tipo=" + Tipo);
            return req;
        };
        this.getRelacionByFormatoId = function (FormatoId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Relacion.php?FormatoId=" + FormatoId);
            return req;
        };
        this.BuscarPlantilla = function (UsuarioId, Estado) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/Relacion.php?UsuarioId_all=" + UsuarioId + "&Estado_all=" + Estado
            });
            return req;
        };
    }]);

