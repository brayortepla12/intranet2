app.service("SectorService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.getSectores = function () {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Sector.php");
        return req;
    };
}]);



