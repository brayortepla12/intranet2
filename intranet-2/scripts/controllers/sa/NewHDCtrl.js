app.controller('NewHDCtrl', ['$scope', '$rootScope', 'PacienteSAService', 'HDService', 'HDFactory',
  function ($scope, $rootScope, PacienteSAService, HDService, HDFactory) {
    // Variables
    const vm = $scope
    vm.PacienteNA = null
    vm.Sectores = HDFactory.data.Sectores || []
    vm.Variables = HDFactory.data.Variables || []
    vm.Tipo = ''
    vm.TOTALC = 0
    vm.HD = {
      Fecha: moment().format('YYYY-MM-DD'),
      Hora: moment().format('HH:mm:ss'),
      Distribucion: '',
      // Tipo: ValidarFecha(),
      Sector: '',
      Detalles: [],
      Responsable: $rootScope.username.NombreCompleto,
      UResponsableId: $rootScope.username.UserId,
      ResponsableId: $rootScope.username.PersonaId,
      CreatedBy: $rootScope.username.NombreCompleto
    }
    vm.items = []

    // EVENTOS
    vm.AddPacientes = () => {
      HDService.VerificarPaciente(vm.PacienteNA.NOADMISION, vm.HD.Distribucion, vm.HD.FechaAPreparar).then(r => {
        if (r.data.length > 0  && r.data[0]) {
          const p = vm.HD.Detalles.filter(x => x.NOADMISION === vm.PacienteNA.NOADMISION)
          if (p.length === 0) {
            vm.PacienteNA.Seleccionado = true
            vm.PacienteNA.Trasladado = true
            $scope.$broadcast('angucomplete-alt:clearInput', 'admision_input');
            vm.HD.Detalles.push(vm.PacienteNA)
            vm.PacienteNA = null
          } else {
            swal('Atención:', 'El paciente ya se encuentra en tu lista', 'warning')
          }
        } else {
          swal('Atención:', 'El paciente ya tiene un alimento cargado para ' + vm.HD.Distribucion, 'warning')
        }
      })
    }
    vm.LoadPaciente = (o) => {
      if (o) {
        vm.PacienteNA = o.originalObject
      }
    }
    vm.ChangeSector = () => {
      GetPacientes()
    }
    
    // ON MENSAJES
    vm.$on('create-hd', function(ev, args){
      CreateHD()
    });
    vm.$on('init-nhd', (ev, args) => {
      GetSectores()
      GetVariables()
    })  

    // CREATE
    function CreateHD () {
      let r = [vm.TOTALC].some(function (val) {
        return val > 0
      })
      let Verificar = []
      Verificar = vm.HD.Detalles.filter(x => {
        return x.Seleccionado && !x.TipoId
      })

      if (!moment(vm.HD.FechaAPreparar, 'YYYY-MM-DD', true).isValid()) {
        swal('Error', 'Debes seleccionar una fecha a preparar', 'error')
      } else if (vm.HD.Detalles.length === 0 || !r) {
        swal('Error', 'La hoja no puede ir en blanco', 'error')
        return false
      } else if (Verificar.length > 0) {
        swal('Error', 'Tienes un paciente seleccionado sin seleccionar el tipo de alimento, por favor desactivelo', 'error')
        return false
      } else {
        let isec = _.findIndex(vm.Sectores, {
          Sector: vm.HD.Sector
        })
        vm.HD.Descripcion = vm.Sectores[isec].Descripcion
        let obj = {
          HD: JSON.stringify(vm.HD)
        }
        $rootScope.$broadcast('LOADING')
        HDService.postHD(obj).then((d) => {
          $rootScope.$broadcast('NOLOADING')
          if (typeof d.data !== 'string') {
            swal('Enhorabuena', 'Se ha guardado los datos con exito', 'success')
            vm.HD = {
              Fecha: moment().format('YYYY-MM-DD'),
              Hora: moment().format('HH:mm:ss'),
              // Tipo: ValidarFecha(),
              Sector: '',
              Detalles: [],
              Responsable: $rootScope.username.NombreCompleto,
              UResponsableId: $rootScope.username.UserId,
              ResponsableId: $rootScope.username.PersonaId,
              CreatedBy: $rootScope.username.NombreCompleto
            }
            $rootScope.$broadcast('get-hds')
          } else {
            swal('Error', d.data, 'error')
          }
        }).catch(e => {
          $rootScope.$broadcast('NOLOADING')
        })
      }
    }

    // CONSULTAS
    function GetSectores() {
      if (vm.Sectores.length === 0) {
        HDService.getSectores().then(function (c) {
          vm.Sectores = Array.isArray(c.data) ? c.data : []
          HDFactory.data.Sectores = vm.Sectores
        }).catch(error => {
          swal('Error', error.stack || error, 'error')
        })
      }
    }
    function GetVariables() {
      if (vm.Variables.length === 0) {
        HDService.getVariables().then(function (c) {
          vm.Variables = Array.isArray(c.data) ? c.data : []
          HDFactory.data.Variables = vm.Variables
        }).catch(error => {
          swal('Error', error.stack || error, 'error')
        })
      }
    }
    function GetPacientes() {
      let obj = {
        Sector: vm.HD.Sector,
        Usuario: $rootScope.username.Email
      }
      PacienteSAService.getPacientesBySector(obj).then(function (d) {
        if (Array.isArray(d.data) && d.data.length > 0) {
          d.data.forEach(x => {
            x.Seleccionado = !x.Suspendido ? true : false
          })
          vm.HD.Detalles = d.data
        } else {
          vm.HD.Detalles = []
        }
      }).catch(error => {
        swal('Error', error.stack || error, 'error')
      })
    }

    // WATCHERS
    vm.$watch('HD.Detalles', (lst) => {
      vm.TOTALC = 0
      if (vm.Variables.length === 0) {
        return false
      }
      for (let i in vm.Variables) {
        vm.Variables[i].TOTALC = 0
      }
      for (let i in lst) {
        if (!lst[i].Seleccionado) {
          continue // saltamos los que no estan seleccionados
        }
        let Vindex = _.findIndex(vm.Variables, {
          VariableId: Number(lst[i].TipoId)
        })
        if (Vindex >= 0) {
          lst[i].Tipo = vm.Variables[Vindex].Abrv
          vm.Variables[Vindex].TOTALC++
          vm.TOTALC++
        }
      }
      // console.table(vm.Variables)
    }, true)
  }
])