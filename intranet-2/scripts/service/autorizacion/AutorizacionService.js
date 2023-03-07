app.service("AutorizacionService", ["$http", "cfpLoadingBar", function ($http,cfpLoadingBar) {
        this.ProgramarEmail = function (fd) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: '/Polivalente/api/EmailAutorizacion.php',
                data: fd,
                headers: {'Content-Type': undefined},
            });
            return req;
        };
        this.postProtocolo = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/Autorizacion.php",
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
        this.getAllProtocoloAutorizacion = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Autorizacion.php");
            return req;
        };
        this.getAutorizaciones = function (Dia,Mes,Year) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/Autorizacion.php?MenaSoft=True&Dia=${Dia}&Mes=${Mes}&Year=${Year}`);
            return req;
        };
    }]);


