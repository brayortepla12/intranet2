app.service("RolService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
  this.getRoles = function (UsuarioId) {
    cfpLoadingBar.start();
    var req = $http({
      method: 'GET',
      url: "/Polivalente/api/Usuario.php?RolesByUsuarioId=" + UsuarioId
    });
    return req;
  };
  this.getRolesByLider = function (ColUId, UsuarioId) {
    cfpLoadingBar.start();
    var req = $http({
      method: 'GET',
      url: `/Polivalente/api/Usuario.php?ColUId=${ColUId}&RolesByUsuarioLiderId=${UsuarioId}`
    });
    return req;
  };
}]);