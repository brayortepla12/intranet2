app.service("TmEventoService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.postTmEvento = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/TmEvento.php",
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
        
        this.putTmEvento = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/TmEvento.php",
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
        
        this.deleteTmEvento = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'DELETE',
                url: "/Polivalente/api/TmEvento.php",
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
        
        this.getEventos = function (TipoEvento) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmEvento.php?TipoEvento=" + TipoEvento);
            return req;
        };
        
        this.getEventoByEventoId = function (EventoId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmEvento.php?EventoId=" + EventoId);
            return req;
        };
        this.getEventosByMaternaId = function (MaternaId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmEvento.php?MaternaId=" + MaternaId);
            return req;
        };
        
        this.getEventosByMaternaIdMenosEste = function (MaternaId, EventoId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmEvento.php?MaternaId_left=" + MaternaId + "&EventoId_left=" + EventoId);
            return req;
        };
        
        
    }]);



