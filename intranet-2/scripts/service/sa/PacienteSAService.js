app.service("PacienteSAService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.getPacientesBySector = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/hd/HD.php",
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

        this.getPacientesByNoAdmision = function (NoAdmision) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/hd/HD.php?NOADMISION=" + NoAdmision,
            });
            return req;
        };
    }]);



