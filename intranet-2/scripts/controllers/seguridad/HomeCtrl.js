'use strict'
app.controller('HomeCtrl', ['$scope', '$rootScope', '$state', 'SesionService', 'HomeService',
  function ($scope, $rootScope, $state, SesionService, HomeService) {
    const vm = $scope
    vm.cargado = false

    vm.GotoUrl = (url) => {
      console.log(url)
      if (url) {
        $state.go(url)
      } else {
        swal('Atención', 'NO TIENES PERMISO PARA ACCEDER A ESTE MODULO', 'warning')
      }
    }

    function _init() {
      var decrypted = SesionService.get('UserData_Polivalente')
      $rootScope.username = decrypted
      var promise = HomeService.getModulosbyUserId(decrypted.UserId)
      promise.then(function (d) {
        vm.Modulos = d.data
        if (typeof d.data === 'string') {
          swal('Hubo un Error', d.data, 'error')
        } else {
          for (var i in vm.Modulos) {
            if (vm.Modulos[i].Nombre) {
              const name = vm.Modulos[i].Nombre.replaceAll(' ', '')
              vm[name] = vm.Modulos[i].State
            }
          }
          vm.cargado = true
        }
      }, function (e) {
        swal('Hubo un Error', e, 'error')
      })
    }
    _init()
  }
])