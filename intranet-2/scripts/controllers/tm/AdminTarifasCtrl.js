app.controller('AdminTarifasCtrl', ["$scope", "$rootScope", "SesionService", "TarifaService", "DepartamentoService", "MunicipioService",
  function ($scope, $rootScope, SesionService, TarifaService, DepartamentoService, MunicipioService) {
    // VARIABLES
    let vm = $scope
    const u = SesionService.get("UserData_Polivalente")
    if (u != undefined) {
      $rootScope.username = u
    }
    vm.Tarifas = []
    vm.IsLoading = false
    vm.ET = false
    vm.Departamentos = []
    vm.MunicipiosOrigen = []
    vm.MunicipiosDestino = []
    vm.Otros = [
      {
        OtroId: 1,
        Nombre: 'TAXI'
      },
      {
        OtroId: null,
        Nombre: 'NINGUNO'
      }
    ]
    // EVENTOS
    vm.ActualizarTarifa = () => {
      if (vm.IsLoading) {
        return false
      }
      vm.Tarifa.ModifiedBy = u.NombreCompleto
      const obj = {
        Tarifa: JSON.stringify(vm.Tarifa)
      }
      vm.IsLoading = true
      TarifaService.putTarifa(obj).then(r => {
        vm.IsLoading = false
        vm.Tarifas = []
        vm.Atras()
        getTarifas()
      }).catch(e => {
        vm.IsLoading = false
      })
    }
    vm.Atras = () => {
      vm.ET = false
    }
    vm.EditTarifa = (TarifaId) => {
      const i = vm.Tarifas.findIndex(x => x.TarifaId === TarifaId)
      vm.Tarifa = angular.copy(vm.Tarifas[i])
      GetMunicipioByDepartamento(vm.Tarifa.DepartamentoOrigenId, 'MunicipiosOrigen')
      GetMunicipioByDepartamento(vm.Tarifa.DepartamentoDestinoId, 'MunicipiosDestino')
      vm.ET = true
    }
    vm.ChangeDepartamento = function (dptId, variable) {
      GetMunicipioByDepartamento(dptId, variable)
    }
    vm.ChangeMunicipio = function () {
      const origen = vm.MunicipiosOrigen.find(x => x.CiudadId === vm.Tarifa.OrigenId)
      const destino = vm.MunicipiosDestino.find(x => x.CiudadId === vm.Tarifa.DestinoId)
      vm.Tarifa.Nombre = `DE ${origen.Ciudad} A ${destino.Ciudad}`.toUpperCase() 
    }
    // CONSULTAS
    function GetDepartamentos() {
      DepartamentoService.GetDepartamentos().then(function (r) {
        vm.Departamentos = r.data
      })
    }
    function GetMunicipioByDepartamento(dptId, variable) {
      MunicipioService.GetMunicipiosByDepartamentoId(dptId).then(function (r) {
        vm[variable] = r.data
      })
    }
    function getTarifas() {
      TarifaService.getTarifas().then(r => {
        vm.Tarifas = r.data.data
      })
    }

    function _init() {
      getTarifas()
      GetDepartamentos()
    }
    _init()
  }
])