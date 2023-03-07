app.service("TmLiderService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.postTmLider = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/TmLider.php",
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
        
        this.putTmLider = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/TmLider.php",
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
        
        this.getLideres = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmLider.php");
            return req;
        };
        
        this.getLiderByLiderId = function (LiderId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmLider.php?LiderId=" + LiderId);
            return req;
        };
        
        this.GetLiderByMunicipioId = function (MunicipioId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmLider.php?MunicipioId=" + MunicipioId);
            return req;
        };
    }]);



