app.service("ActaSistemasService",[ "$http", function ($http) {
   this.postActaSistemas = function (obj) {
        var req = $http({
            method: 'POST',
            url: "/Polivalente/api/ActaSistemas.php",
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
    
    this.GetAll = function () {
        var req = $http({
            method: 'GET',
            url: "/Polivalente/api/ActaSistemas.php?Actas=True"
        });
        return req;
    };
    
    this.GetDetallesByActaId = function (ActaId) {
        var req = $http({
            method: 'GET',
            url: "/Polivalente/api/ActaSistemas.php?ActaId=" + ActaId
        });
        return req;
    };
}]);

