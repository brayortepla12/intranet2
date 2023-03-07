app.service("ProtocoloService", ["$http", function ($http) {
        this.postProtocolo = function (obj) {
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/Protocolo.php",
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
        this.DeleteProtocolo = function (obj) {
            var req = $http({
                method: 'DELETE',
                url: "/Polivalente/api/Protocolo.php",
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
        this.putProtocolo = function (obj) {
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/Protocolo.php",
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
        this.GetAllProtocolo = function (UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/Protocolo.php?UsuarioId_all=" + UsuarioId
            });
            return req;
        };
        this.GetProtocoloById = function (ProtocoloId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/Protocolo.php?ProtocoloId=" + ProtocoloId
            });
            return req;
        };
    }]);


