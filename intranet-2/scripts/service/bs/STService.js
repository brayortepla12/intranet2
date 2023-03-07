app.service("STService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.getSTs = function (FFrom, FTo) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/ST.php?FFrom=${FFrom}&FTo=${FTo}`);
            return req;
        };
    }]);
