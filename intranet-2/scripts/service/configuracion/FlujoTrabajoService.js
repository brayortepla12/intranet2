app.service("FlujoTrabajoService", ["$http", function ($http) {
        this.postFlujoTrabajo = function (obj) {
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/FlujoTrabajo.php",
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
        this.DeleteFlujoTrabajo = function (obj) {
            var req = $http({
                method: 'DELETE',
                url: "/Polivalente/api/FlujoTrabajo.php",
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
        this.putFlujoTrabajo = function (obj) {
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/FlujoTrabajo.php",
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
        this.GetAllFlujoTrabajo = function (UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/FlujoTrabajo.php?UsuarioId_all=" + UsuarioId
            });
            return req;
        };
        this.GetFlujoTrabajoById = function (FlujoTrabajoId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/FlujoTrabajo.php?FlujoTrabajoId=" + FlujoTrabajoId
            });
            return req;
        };
        this.GetFlujoTrabajoByProtocoloId = function (ProtocoloId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/FlujoTrabajo.php?ProtocoloId=" + ProtocoloId
            });
            return req;
        };
    }]);


