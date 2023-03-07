app.service("NotasService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.GetNotas = function (HistoriaId) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/thc/Notas.php?HistoriaId=${HistoriaId}`);
            return req;
        };
        this.postNotas = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/thc/Notas.php",
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


