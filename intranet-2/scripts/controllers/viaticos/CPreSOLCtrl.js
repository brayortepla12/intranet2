app.controller('CPreSOLCtrl', ["$scope", "$rootScope", "ViaticoService", "SesionService", "SedeService",
  function ($scope, $rootScope, ViaticoService, SesionService, SedeService) {
    // VARIABLES
    let vm = $scope
    const u = SesionService.get("UserData_Polivalente")
    if (u != undefined) {
      $rootScope.username = u
    }
    vm.IsLoading = false
    vm.ShowDatos = false
    vm.Sedes = []
    vm.Departamentos = []
    vm.Sol = {
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
    vm.CrearPreSol = () => {
      if (!vm.Datos.$valid) {
        angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus()
        return false
      }
      if (vm.IsLoading) {
        return false
      } else {
        vm.Sol.DepartamentoOrigen = vm.Departamentos.find(x => x.DepartamentoId == vm.Sol.DepartamentoOrigenId).Departamento
        vm.Sol.DepartamentoDestino = vm.Departamentos.find(x => x.DepartamentoId == vm.Sol.DepartamentoDestinoId).Departamento
        vm.Sol.MunicipioOrigen = vm.MunicipiosOrigen.find(x => x.CiudadId == vm.Sol.MunicipioOrigenId).Ciudad
        vm.Sol.MunicipioDestino = vm.MunicipiosDestino.find(x => x.CiudadId == vm.Sol.MunicipioDestinoId).Ciudad
        vm.Sol.Sede = vm.Sedes.find(x => x.SedeId == vm.Sol.SedeId).Nombre
        const obj = {
          PreSol: JSON.stringify(vm.Sol)
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
    async function GetMunicipiosByDptId(DtpId){
      return await ViaticoService.GetMunicipioByDptId(DtpId)
    }
    
    vm.ChangeIsExterno = () => {
      vm.Sol.ResIsExterno = !vm.Sol.ResIsExterno
      vm.ShowDatos = true
    }
    // HELPERS
    vm.$on('$destroy', function () {
      vm = null
      $scope = null
    })

    function _init_() {
      GetSede()
      GetDepartamentos()
    }
    _init_();
  }
])