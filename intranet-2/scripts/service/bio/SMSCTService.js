app.service("SMSCTService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.GetSMSMes = function (Year, Mes) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/SMSCT.php?Year=" + Year + "&Mes=" + Mes);
            return req;
        };
    }]);
