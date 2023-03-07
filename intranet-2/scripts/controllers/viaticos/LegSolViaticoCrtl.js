app.controller('LegSolViaticoCtrl', ["$scope", "$rootScope", "LegalizacionService", "SesionService",
  function ($scope, $rootScope, LegalizacionService, SesionService) {
    // VARIABLES
    let vm = $scope
    vm.TotalEgreso = 0
    vm.IsLoading = false
    const u = SesionService.get("UserData_Polivalente")
    if (u != undefined) {
      $rootScope.username = u
    }
    vm.Leg = {}
    // EVENTOS
    vm.LegalizarViatico = () => {
      if (!vm.Datos.$valid) {
        angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus()
        return false
      } else if (vm.TotalEgreso === 0) {
        swal("Error!",'Debes añadir un concepto como minimo', 'error')
        return false
      }
      if (vm.IsLoading) {
        return false
      } else {
        const obj = {
          LegalizarViatico: JSON.stringify(vm.Leg)
        }
        vm.IsLoading = true
        LegalizacionService.post(obj).then(r => {
          vm.IsLoading = false
          if (r.data.data) {
            window.opener.notify(r.data.data)
            window.close()
          } else {
            swal("Error!", r.data.error, 'error')
          }
        }).catch(err => { vm.IsLoading = false })
      }
    }
    vm.EliminarConcepto = (i) => {
      vm.Leg.Conceptos.splice(i, 1)
    }
    vm.AddConcepto = () => {
      const concepto = window.TipoLegalizacion === 'Normal' ? {
        Fecha: null,
        Factura: "",
        Responsable: "",
        Concepto: "",
        Valor: 0
      } : {
        Fecha: null,
        NombresPaciente: "",
        Origen: "",
        Destino: "",
        Factura: "",
        Responsable: "",
        Tripulacion: "",
        Concepto: "",
        Valor: 0
      }
      vm.Leg.Conceptos.push(concepto)
    }
    // HELPERS
    function __init__() {
      if (!window.SolicitudId && window.TipoLegalizacion === 'Normal') {
        window.close()
      } else if (window.TipoLegalizacion === 'Normal') {
        vm.Leg = {
          ANombreDe: "",
          ResCedula: "",
          SolicitudId: "",
          ResPersonaId: "",
          Descripcion: "",
          ProcesoId: 2,
          OrdenEncurso: 0,
          Conceptos: [
            {
              Fecha: null,
              Factura: "",
              Responsable: "",
              Concepto: "",
              Valor: 0
            }
          ],
          NC: '',
          RC: '',
          DL: 0,
          CreatedBy: u.NombreCompleto
        }
        vm.Leg.ANombreDe = window.ANombreDe
        vm.Leg.ResCedula = window.ResCedula
        vm.Leg.SolicitudId = window.SolicitudId
        vm.Leg.ResPersonaId = $rootScope.username.PersonaId
        vm.Leg.ProcesoId = window.ProcesoId
        vm.Leg.OrdenEncurso = window.OrdenEncurso
        vm.Leg.TipoLegalizacion = window.TipoLegalizacion
      } else if (window.TipoLegalizacion === 'Aguachica') {
        vm.Leg = {
          Fecha: "",
          ResPersonaId: u.PersonaId,
          ProcesoId: 3,
          OrdenEncurso: 0,
          Conceptos: [
            {
              Fecha: null,
              NombresPaciente: "",
              Origen: "",
              Destino: "",
              Factura: "",
              Responsable: "",
              Tripulacion: "",
              Concepto: "",
              Valor: 0
            }
          ],
          CreatedBy: u.NombreCompleto
        }
        vm.Leg.TipoLegalizacion = window.TipoLegalizacion
      }
    }
    // watchers
    vm.$watch('Leg.Conceptos', (lst) => {
      vm.TotalEgreso = 0
      if (lst.length === 0) {
        return false
      }
      for (let i in lst) {
        if (Number(lst[i].Valor) !== 0) {
          vm.TotalEgreso += Number(lst[i].Valor)
        }
      }
    }, true)
    __init__()
  }
])