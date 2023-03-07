app.controller('VENSOLCtrl', ["$scope", "$rootScope", "SOLFactory", "SesionService",
  function ($scope, $rootScope, SOLFactory, SesionService) {
    // VARIABLES
    let vm = $scope
    vm.PREFIJO = ''
    const u = SesionService.get("UserData_Polivalente")
    if (u != undefined) {
      $rootScope.username = u
    } else {
      vm.Sol = {}
      vm.PREFIJO = ''
      alert("No existe un usuario")
    }
    function _init(){
      setTimeout(() => {
        if (window.SOL) {
          const data = angular.copy(JSON.parse(window.SOL))
          SOLFactory.data.Sol = data.Solicitud
          SOLFactory.data.PREFIJO = data.PREFIJO
          vm.PREFIJO = data.PREFIJO
        } else {
          SOLFactory.data.PREFIJO = angular.copy(window.PREFIJO)
          SOLFactory.data.ReporteId = angular.copy(window.ReporteId)
          vm.PREFIJO = window.PREFIJO
        }
        vm.$apply()
      }, 1000)
    }
    _init()

    // ONs
    vm.$on('saved-reporte', () => {
      window.opener.notify()
      window.close()
    })
    vm.$on('$destroy', function () {
      vm = null
      $scope = null
    })
  }])