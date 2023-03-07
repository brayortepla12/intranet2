app.controller('GSOLCtrl', ["$scope", "$rootScope", "ViaticoService", "SesionService", "SedeService",
  function ($scope, $rootScope, ViaticoService, SesionService, SedeService) {
    // VARIABLES
    let vm = $scope
    vm.TDias = 0
    vm.TValor = 0
    const u = SesionService.get("UserData_Polivalente")
    if (u != undefined) {
      $rootScope.username = u
    }
    vm.PRESOLICITUDID = null
    vm.IsLoading = false
    vm.Sedes = []
    vm.Departamentos = []
    vm.ShowDatos = false
    vm.CompShowDatos = false
    vm.Sol = {}
    // EVENTOS
    vm.LoadPersona = (o) => {
      if (o) {
        vm.Sol.ResPersonaId = o.originalObject.PersonaId
        vm.Sol.ResCedula = o.originalObject.Cedula
        vm.Sol.ResPrimerNombre = o.originalObject.PrimerNombre
        vm.Sol.ResSegundoNombre = o.originalObject.SegundoNombre
        vm.Sol.ResPrimerApellido = o.originalObject.PrimerApellido
        vm.Sol.ResSegundoApellido = o.originalObject.SegundoApellido
        vm.Sol.ResCargo = o.originalObject.Cargo
        vm.Sol.ResCelular = o.originalObject.Celular
        vm.Sol.ResCorreo = o.originalObject.Email
        vm.ShowDatos = true
      } else {
        vm.Sol.ResPersonaId = null
        vm.Sol.ResCedula = ''
        vm.Sol.ResPrimerNombre = ''
        vm.Sol.ResSegundoNombre = ''
        vm.Sol.ResPrimerApellido = ''
        vm.Sol.ResSegundoApellido = ''
        vm.Sol.ResCargo = ''
        vm.Sol.ResCelular = ''
        vm.Sol.ResCorreo = ''
        vm.ShowDatos = false
      }
    }
    vm.CompletarSolicitud = () => {
      if (!vm.Datos.$valid) {
        angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus()
        return false
      } else if (vm.TValor === 0) {
        swal("Error!",'Debes añadir un concepto como minimo', 'error')
        return false
      } else if (vm.Sol.TipoSolicitud !== 'PASAJE' && vm.Sol.TipoSolicitud !== 'GASTOS DE REPRESENTACIÓN') {
        swal("Error!",'Debes seleccionar un tipo de solicitud valido', 'error')
        return false
      }
      if (vm.IsLoading) {
        return false
      } else {
        // valores 
        vm.Sol.DepartamentoOrigen = vm.Departamentos.find(x => x.DepartamentoId == vm.Sol.DepartamentoOrigenId).Departamento
        vm.Sol.DepartamentoDestino = vm.Departamentos.find(x => x.DepartamentoId == vm.Sol.DepartamentoDestinoId).Departamento
        vm.Sol.MunicipioOrigen = vm.MunicipiosOrigen.find(x => x.CiudadId == vm.Sol.MunicipioOrigenId).Ciudad
        vm.Sol.MunicipioDestino = vm.MunicipiosDestino.find(x => x.CiudadId == vm.Sol.MunicipioDestinoId).Ciudad
        vm.Sol.Sede = vm.Sedes.find(x => x.SedeId == vm.Sol.SedeId).Nombre
        vm.Sol.PreSolicitudId = vm.PRESOLICITUDID
        vm.Sol.ProcesoId = 1
        vm.Sol.OrdenEnCurso = 0
        const obj = {
          CompletarSol: JSON.stringify(vm.Sol)
        }
        vm.IsLoading = true
        ViaticoService.postViaticos(obj).then(r => {
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
    vm.GetMunicipiosOrigen = () => {
      const p = GetMunicipiosByDptId(vm.Sol.DepartamentoOrigenId)
      p.then(r => {
        vm.MunicipiosOrigen = r.data.data ? r.data.data : []
      })
    }
    vm.GetMunicipiosDestino = () => {
      const p = GetMunicipiosByDptId(vm.Sol.DepartamentoDestinoId)
      p.then(r => {
        vm.MunicipiosDestino = r.data.data ? r.data.data : []
      })
    }
    vm.EliminarConcepto = (i) => {
      vm.Sol.Conceptos.splice(i, 1)
    }
    vm.AddConcepto = () => {
      vm.Sol.Conceptos.push(
        {Concepto: "", Dias: 1, Valor: 0, Legalizable: 0}
      )
    }
    vm.ChangeConceptoBandera = (i) => {
      vm.Conceptos[i].Legalizable = !vm.Conceptos[i].Legalizable
    }
    // CONSULTAS
    function GetDepartamentos(){
      ViaticoService.GetDepartamentos().then(r => {
        vm.Departamentos = r.data.data ? r.data.data : []
      })
    }
    function GetSede() {
      SedeService.getAllSedeByUserId_TA(SesionService.get("UserData_Polivalente").UserId, 'pol').then(function (r) {
        vm.Sedes = r.data ? r.data : []
      })
    }
    function GetConceptos() {
      ViaticoService.GetConceptos().then(function (r) {
        const conceptos = r.data.data ? r.data.data : []
        conceptos.forEach(x => {
          x.Legalizable = x.Legalizable ? true : false
        })
        vm.Sol.Conceptos = conceptos
      })
    }
    async function GetMunicipiosByDptId(DtpId){
      return await ViaticoService.GetMunicipioByDptId(DtpId)
    }
    // HELPERS
    vm.$on('$destroy', function () {
      vm = null
      $scope = null
    })

    function _init_() {
      const normal = {
        Fecha: moment().format('YYYY-MM-DD'),
        SedeId: '1',
        Sede: 'CIELD',
        NombreSolicita: u.NombreCompleto,
        CargoSolicita: u.Cargo,
        UsuarioSolicitaId: u.UserId,
        ResPrimerNombre: null,
        ResSegundoNombre: null,
        ResPrimerApellido: null,
        ResSegundoApellido: null,
        ResCedula: null,
        ResCelular: null,
        ResCargo: null,
        DescripcionSolicitud: null,
        DepartamentoOrigenId: '',
        MunicipioOrigenId: '',
        DepartamentoDestinoId: '',
        MunicipioDestinoId: '',
        ResIsExterno: false,
        ResPersonaId: null
      }
      if (Number.isInteger(window.PreSolicitudId)) { //Number.isInteger(window.PreSolicitudId)
        vm.PRESOLICITUDID = window.PreSolicitudId
        ViaticoService.GetPreSolicitudViaticoById(window.PreSolicitudId).then(r => {
          vm.Sol = r.data.data ? r.data.data[0] : normal
          vm.Sol.ResIsExterno = vm.Sol.ResIsExterno ? true : false
          vm.ShowDatos = true
          vm.CompShowDatos = true
          vm.GetMunicipiosOrigen()
          vm.GetMunicipiosDestino()
          GetConceptos()
        })
      } else {
        vm.Sol = normal
        vm.ShowDatos = false
        GetConceptos()
      }
      GetSede()
      GetDepartamentos()
    }
    _init_()

    // watchers
    vm.$watch('Sol.Conceptos', (lst) => {
      vm.TDias = 0
      vm.TValor = 0
      if (lst.length === 0) {
        return false
      }
      for (let i in lst) {
        if (Number(lst[i].Valor) !== 0) {
          vm.TDias += Number(lst[i].Dias)
          vm.TValor += Number(lst[i].Valor)
        }
      }
    }, true)
  }
])