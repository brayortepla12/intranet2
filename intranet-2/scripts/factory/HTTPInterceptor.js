app.factory('DATAHttpInterceptor',
  ["$q", "$rootScope", "$location", "SesionService", "SesionFactory",
    function ($q, $rootScope, $location, SesionService, SesionFactory) {
      return {
        request: function (config) {
          if (config.url.indexOf("/api/") !== -1) {
            $rootScope.username = SesionService.get('UserData_Polivalente');
            if ($rootScope.username) {
              config.headers['Authorization'] = 'Bearer ' + $rootScope.username.key;
            } else {
              //                    $location.path('/login');
            }
          }
          return config || $q.when(config);
        },
        responseError: function (response) {
          // Unauthorized
          if (response.status == 401) {
            $rootScope.username = null;
            SesionFactory.Menu = [];
            SesionService.remove("UserData_Polivalente");
            SesionService.remove("MenuAPP_Polivalente");
            $location.path('/login');
          }
          return $q.reject(response);
        }
      };
    }
  ]);