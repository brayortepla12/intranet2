'use strict';
app.controller('AuditoriaSesion', ["$scope", "SesionService", "$rootScope", "$state",
    function ($scope, SesionService, $rootScope, $state) {
        var currentState = $state.current.name;
        $scope.navegacion = currentState;
        var MenuApp = SesionService.get("MenuAPP_Polivalente");
        var decrypted = SesionService.get("UserData_Polivalente");
        $rootScope.username = decrypted;
        function _init() {
            if (decrypted) {
                if (MenuApp.length > 0) {
                    if (!isInArray(MenuApp, currentState)) {
                        // buscamos los siguientes si no pertenece al estado inicial
                        $state.go(MenuApp[0].State);
                    }
                } else {
                    SesionService.remove("UserData_Polivalente");
                    SesionService.remove("MenuAPP_Polivalente");
                    $state.go("login");
                }
            } else {
                SesionService.remove("UserData_Polivalente");
                SesionService.remove("MenuAPP_Polivalente");
                $state.go("login");
            }
        }
        
        _init();
        function isInArray(list, item) {
            for (var i in list) {
                if (list[i].State === item) {
                    return true;
                }
            }
            return false;
        }
    }]);