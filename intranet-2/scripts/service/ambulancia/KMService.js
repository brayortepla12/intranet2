app.service("KMService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.PostKM = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: "/Polivalente/api/KMAmbulancia.php",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transformRequest: function(obj) {
                        var str = [];
                        for(var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                        return str.join("&");
                    },
                    data: obj
                  });
        return req;
    };
    this.PutKM = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'PUT',
                    url: "/Polivalente/api/KMAmbulancia.php",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transformRequest: function(obj) {
                        var str = [];
                        for(var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                        return str.join("&");
                    },
                    data: obj
                  });
        return req;
    };
    this.getAllKM = function () {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/KMAmbulancia.php");
        return req;
    };
    this.getLastKMByHojaVidaId = function (HojaVidaId ) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/KMAmbulancia.php?HojaVidaId=" +HojaVidaId);
        return req;
    };
}]);

