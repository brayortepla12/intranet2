app.controller('NavCtrl', ["$scope", "SesionService", "$rootScope", "$state", "UsuarioService", "PersonaService", "SesionFactory",
  function ($scope, SesionService, $rootScope, $state, UsuarioService, PersonaService, SesionFactory) {
    let vm = $scope
    vm.MenuApp = []
    var currentState = $rootScope.currentState
    vm.navegacion = currentState
    vm.NombreUsuario = "hola"
    vm.Foto = ""
    vm.Cargo = "Cargo"
    vm.opcion = ''

    function _init() {
      var decrypted = SesionService.get("UserData_Polivalente")
      if (decrypted) {
        vm.NombreUsuario = decrypted.NombreCompleto
        vm.Foto = decrypted.Url_Foto
        vm.Cargo = decrypted.Cargo
        vm.MenuApp = SesionService.get("MenuAPP_Polivalente")
        if (!vm.MenuApp) {
          let obj = {
            UserId: decrypted.UserId,
            Email: decrypted.Email
          }
          UsuarioService.Getpermisos(obj).then(function (d) {
            if (d.data.length > 0) {
              vm.MenuApp = d.data
              SesionFactory.Menu = d.data
              SesionService.set(vm.MenuApp, "MenuAPP_Polivalente")
              vm.opcion = currentState.split(".")[0]
              if (!isInArray(d.data, currentState)) {
                // buscamos los siguientes si no pertenece al estado inicial
                for (var i in vm.MenuApp) {
                  if (isInArray(currentState, vm.MenuApp[i])) {

                    $state.go(vm.MenuApp[i])
                  }
                }
              }
              $('.current-page').parent().css('display', 'block')
            } else {
              $rootScope.username = null
              vm.MenuApp = []
              SesionService.remove("UserData_Polivalente")
              $state.go("login")

            }
          }, function (e) {
            swal("Hubo un Error", e, "error")
          })
        }

        $rootScope.username = decrypted
        if (!$rootScope.username.PersonaId) {
          ValidateRegUser($rootScope.username.UserId)
        }
      } else {
        SesionService.remove("UserData_Polivalente")
        $state.go("login")
      }
    }

    function ValidateRegUser(UserId) {
      PersonaService.VerificarRegUser(UserId).then((d) => {
        if (d.data == "false") {
          $state.go("app.reg_usuario")
        }
      })
    }
    _init()
    vm.logout = function () {
      $rootScope.username = null
      vm.MenuApp = []
      SesionService.remove("UserData_Polivalente")
      SesionService.remove("MenuAPP_Polivalente")
      $state.go("login")
    }

    function isInArray(list, item) {
      for (var i in list) {
        if (list[i].State === item) {
          return true
        }
      }
      return false
    }
    vm.isInArray = function (list, item) {
      for (var i in list) {
        if (list[i].State) {
          if (list[i].State.startsWith(item)) {
            return true
          }
        }
      }
      return false
    }
  }
])