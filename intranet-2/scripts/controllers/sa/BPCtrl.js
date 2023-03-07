app.controller('BPCtrl', ['$scope', '$rootScope', 'HDFactory', 'HDService',
  function ($scope, $rootScope, HDFactory, HDService) {
    let vm = $scope
    // VARIABLES
    vm.Variables = []
    vm.TOTALC = 0
    vm.PacienteNA = null
    vm.HD = {}
    // EVENTOS
    vm.LoadPaciente = (o) => {
      if (o) {
        vm.PacienteNA = o.originalObject
      }
    }
    vm.AddPacientes = () => {
      HDService.VerificarPaciente(vm.PacienteNA.NOADMISION, vm.HD.Distribucion, vm.HD.Fecha).then(r => {
        if (r.data.length > 0  && r.data[0]) {
          const p = HDFactory.HD.Detalles.filter(x => x.NOADMISION === vm.PacienteNA.NOADMISION)
          const p2 = vm.HD.Detalles.filter(x => x.NOADMISION === vm.PacienteNA.NOADMISION)
          if (p.length === 0 && p2.length === 0) {
            vm.PacienteNA.Seleccionado = true
            vm.PacienteNA.Trasladado = true
            $scope.$broadcast('angucomplete-alt:clearInput', 'admision_input');
            vm.HD.Detalles.push(angular.copy(vm.PacienteNA))
            vm.PacienteNA = null
          } else {
            swal('Atención:', 'El paciente ya se encuentra en tu lista', 'warning')
          }
        } else {
          swal('Atención:', 'El paciente ya tiene un alimento cargado para ' + vm.HD.Distribucion, 'warning')
        }
      })
    }
    // ON MENSAGES
    vm.$on('init-bp', (ev, args) => {
      const obj = angular.copy(HDFactory.HD)
      obj.Detalles = []
      vm.HD = obj
      vm.HD.Distribucion = GetDistribucion()
      GetVariables()
    })
    vm.$on('add-paciente-bp', (e, d) => {
      let r = [vm.TOTALC].some(function (val) {
        return val > 0
      })
      let Verificar = []
      Verificar = vm.HD.Detalles.filter(x => {
        return x.Seleccionado && !x.TipoId
      })
      if (vm.HD.Detalles.length === 0 || !r) {
        swal("Error", "No hay pacientes por añadir", "error")
        return false
      } else if (Verificar.length > 0) {
        swal("Error", "Tienes un paciente seleccionado sin seleccionar el tipo de alimento, por favor desactivelo", "error")
        return false
      } else {
        let obj = {
          PHD: JSON.stringify(vm.HD)
        }
        $rootScope.$broadcast('LOADING')
        HDService.postHD(obj).then((d) => {
          $rootScope.$broadcast('NOLOADING')
          if (typeof d.data !== "string") {
            swal("Enhorabuena", "Se ha guardado los datos con exito", "success")
            vm.HD = {}
            $rootScope.$broadcast('back-uhd')
          } else {
            swal("Error", d.data, "error")
          }
        }).catch(e => {
          $rootScope.$broadcast('NOLOADING')
        })
      }
    })
    // CONSULTAS
    function GetVariables() {
      vm.Variables = HDFactory.data.Variables || []
      if (vm.Variables.length === 0) {
        HDService.getVariables().then(function (c) {
          vm.Variables = Array.isArray(c.data) ? c.data : []
          HDFactory.data.Variables = vm.Variables
        }).catch(error => {
          swal('Error', error.stack || error, 'error')
        })
      }
    }
    // HELPERS
    function GetDistribucion () {
      const dlst = ['Desayuno', 'MM', 'Almuerzo', 'MT', 'Cena', 'MN']
      let indice = 0
      dlst.forEach((x, i) => {
        if (vm.HD[x]){
          indice = i
        }
      })
      return dlst[indice]
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
    }, true)
    vm.$on('$destroy', function() {
      vm = null
      $scope = null
    })
  }
])