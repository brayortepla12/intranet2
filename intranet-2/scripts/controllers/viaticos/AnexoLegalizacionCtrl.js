app.controller('AnexoLegalizacionCtrl', ["$scope", "$rootScope", "LegalizacionService", "SesionService",
  function ($scope, $rootScope, LegalizacionService, SesionService) {
    // VARIABLES
    let vm = $scope
    vm.Anexo = null
    const u = SesionService.get('UserData_Polivalente')
    if (u != undefined) {
      $rootScope.username = u
    }
    
    // METHODS
    vm.getAnexoByDetalleLegalizacionId = (DetalleLegalizacionId) => {
      vm.Anexo = null
      LegalizacionService.getAnexoByDetalleLegalizacionId(DetalleLegalizacionId).then(r => {
        if (r.data.data) {
          vm.Anexo = r.data.data[0].Anexo
        }
      })
    }
    // HELPERS
    function getConceptos() {
      LegalizacionService.getConceptosByLegalizacionId(window.LegalizacionId).then(r => {
        vm.Conceptos = r.data.data ? r.data.data : []
      })
    }

    function __init__() {
      if (!window.LegalizacionId) {
        window.close()
      }
      getConceptos()
    }
    __init__()
  }
])