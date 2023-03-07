'use strict'
app.controller('HomeRCtrl', ['$scope', '$rootScope', '$state', 'SesionService', 'HomeService',
  function ($scope, $rootScope, $state, SesionService, HomeService) {
    const vm = $scope
    vm.cargado = false
    vm.Roles = []

    vm.GotoUrl = (r, url) => {
      const rol = vm.Roles.find(x => x.Nombre === r)
      if (url && rol) {
        const encryptData = CryptoJS.AES.encrypt(JSON.stringify($rootScope.username),
          'Franklin Ospino'
        )
        const data = encryptData.toString().replaceAll('/', '\\');
        location.href = url + `#/auth/${data}`
      } else {
        swal('Atención', 'NO TIENES PERMISO PARA ACCEDER A ESTE MODULO', 'warning')
      }
    }

    vm.GotoLavandaria = (r) => {
        const rol = vm.Roles.find(x => x.Nombre === r)
        if (rol) {
            location.href = `http://localhost:8000/auth/token-auth/${$rootScope.username.key}`
        } else {
            swal('Atención', 'NO TIENES PERMISO PARA ACCEDER A ESTE MODULO', 'warning')
        }
    }

    vm.hasRoles = (r) => {
      const rol = vm.Roles.find(x => x.Nombre === r)
      if (rol) {
        return true
      }
      return false
    }

    function _init() {
      const decrypted = SesionService.get('UserData_Polivalente')
      $rootScope.username = decrypted
      const promise = HomeService.getRolesbyEmail($rootScope.username.Email)
      promise.then(function (d) {
        if (d.data.error) {
          swal('Hubo un Error', d.data.error, 'error')
        } else {
          vm.Roles = d.data.data
          vm.cargado = true
        }
      }, function (e) {
        swal('Hubo un Error', e, 'error')
      })
    }
    _init()
  }
])