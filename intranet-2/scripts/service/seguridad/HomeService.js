app.service("HomeService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
  var base = "/Polivalente/api/Modulo.php";
  this.getModulos = function () {
      var req = $http.get(base);
      return req;
  };
  this.getModulosbyUserId = function (UserId) {
      cfpLoadingBar.start();
      var req = $http({
                  method: 'GET',
                  url: "/Polivalente/api/Modulo.php?UserId=" + UserId,
                  data: UserId
                });
      return req;
  };
  this.getRolesbyEmail = function (Email) {
      cfpLoadingBar.start();
      var req = $http({
                  method: 'GET',
                  url: "/Polivalente/api/Usuario.php?Email_r=" + Email
                });
      return req;
  };
}]);