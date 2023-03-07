app.service("TmEPSService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.postTmEPS = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/TmEPS.php",
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
        
        this.putTmEPS = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/TmEPS.php",
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
        
        this.getEPSs = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmEPS.php");
            return req;
        };
        
        this.getEPSByEPSId = function (EPSId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/TmEPS.php?EPSId=" + EPSId);
            return req;
        };
    }]);



