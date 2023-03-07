app.service("HistoriaService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.getHistoriaKrysByNoAdmision = function (NoAdmision) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/thc/Historia.php?NoAdmision=${NoAdmision}`);
            return req;
        };
        this.GetTrazabilidadByHistoriaId = function (HistoriaId) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/thc/Historia.php?TrazaHistoriaId=${HistoriaId}`);
            return req;
        };
        this.GetHistoriaById = function (HistoriaId) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/thc/Historia.php?HistoriaId=${HistoriaId}`);
            return req;
        };
        this.GetMH = function (UsuarioId) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/thc/Historia.php?UsuarioId=${UsuarioId}`);
            return req;
        };
        this.GetMHPR = function (UsuarioId) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/thc/Historia.php?UsuarioIdPR=${UsuarioId}`);
            return req;
        };
        this.postHistoria = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/thc/Historia.php",
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
        this.putHistoria = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/thc/Historia.php",
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


