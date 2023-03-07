app.service("RondaMantService",[ "$http", function ($http) {
    this.putRondaMant = function (obj) {
        var req = $http({
            method: 'PUT',
            url: "/Polivalente/api/pol/RondaMant.php",
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
    this.GetAll = function (UsuarioId, PREFIJO) {
        var req = $http({
            method: 'GET',
            url: "/Polivalente/api/pol/RondaMant.php?UsuarioId=" + UsuarioId + "&PREFIJO=" + PREFIJO
        });
        return req;
    };
    this.GetRondaMantById = (RondaMantId, PREFIJO) => {
        var req = $http({
            method: 'GET',
            url: `/Polivalente/api/pol/RondaMant.php?RondaMantId=${RondaMantId}&PREFIJO_id=${PREFIJO}`
        });
        return req;
    };
    this.postRondaMant = function (obj) {
        var req = $http({
            method: 'POST',
            url: "/Polivalente/api/pol/RondaMant.php",
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

