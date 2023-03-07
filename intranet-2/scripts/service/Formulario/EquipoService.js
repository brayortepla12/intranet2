app.service("EquipoService",[ "$http", function ($http) {
    this.GetAllByServicios = function (obj) {
        var req = $http({
            method: 'POST',
            url: "/Polivalente/api/Equipo.php",
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
    this.GetAllPlantaBySede = function (UserId) {
        var req = $http({
            method: 'GET',
            url: "/Polivalente/api/Equipo.php?UserId=" + UserId,
        });
        return req;
    };
}]);

