app.service("RondaService",[ "$http", function ($http) {
   this.postRondaUsuario = function (obj) {
        var req = $http({
            method: 'POST',
            url: "/Polivalente/api/Ronda.php",
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
    this.putRonda = function (obj) {
        var req = $http({
            method: 'PUT',
            url: "/Polivalente/api/Ronda.php",
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
    this.DeleteRondaByRondaId = function (obj) {
        var req = $http({
            method: 'DELETE',
            url: "/Polivalente/api/Ronda.php",
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
    this.putActividadUsuario = function (obj) {
        var req = $http({
            method: 'PUT',
            url: "/Polivalente/api/Ronda.php",
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
    this.GetAll = function (UsuarioId) {
        var req = $http({
            method: 'GET',
            url: "/Polivalente/api/Ronda.php?RondaUID=" + UsuarioId
        });
        return req;
    };
    this.GetAll_lite = function (UsuarioId) {
        var req = $http({
            method: 'GET',
            url: "/Polivalente/api/Ronda.php?RondaUID_Lite=" + UsuarioId
        });
        return req;
    };
    this.postRondas = function (obj) {
        var req = $http({
            method: 'POST',
            url: "/Polivalente/api/Ronda.php",
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
    
    this.GetAllLite = function (UsuarioId) {
        var req = $http({
            method: 'GET',
            url: "/Polivalente/api/Ronda.php?Lite=" + UsuarioId
        });
        return req;
    };
    this.GetAllByFecha = function (Fecha,UsuarioId) {
        var req = $http({
            method: 'GET',
            url: "/Polivalente/api/Ronda.php?UsuarioId=" + UsuarioId + "&Fecha=" + Fecha
        });
        return req;
    };
    this.GetRondaById = function (RondaId) {
        var req = $http({
            method: 'GET',
            url: "/Polivalente/api/Ronda.php?RondaId=" + RondaId
        });
        return req;
    };
    
    this.GetTareas = function (UserId) {
        var req = $http({
            method: 'GET',
            url: "/Polivalente/api/Ronda.php?Tarea_UserId=" + UserId
        });
        return req;
    };
}]);

