app.controller('TelefonosCtrl', ["$scope", "$rootScope", "$state", "TelService",
  function ($scope, $rootScope, $state, TelService) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    const vm = $scope;
    vm.TELDATA = [];
    vm.TEL = {
      LiderTelefonoId: null,
      Responsable: '',
      Area: '',
      Sede: '',
      Telefono: '',
      TelefonoAnt: '',
      Estado: 'Activo',
    }
    vm.NTEL = false;
    vm.VTEL = false;
    vm.IsLoading = false;
    vm.LTEL = false;
    //</editor-fold>
    //#region (collapsed) SUB Eventos
    vm.LoadPersona = (o) => {
      if (o) {
        vm.TEL.LiderTelefonoId = o.originalObject.personaId
        vm.TEL.Lider = o.originalObject.Nombres
      }
    }
    vm.getTelefonos = () => {
      getTelefonos();
    }
    vm.Atras = () => {
      vm.$broadcast('angucomplete-alt:clearInput')
      vm.NTEL = false
      vm.VTEL = false
      vm.TEL = {
        LiderTelefonoId: null,
        Responsable: '',
        Area: '',
        Sede: '',
        Telefono: '',
        TelefonoAnt: '',
        Estado: 'Activo',
      }
    }
    vm.NewTEL = () => {
      vm.NTEL = true;
      vm.TEL = {
        LiderTelefonoId: null,
        Responsable: '',
        Area: '',
        Sede: '',
        Telefono: '',
        TelefonoAnt: '',
        Estado: 'Activo',
      }
    }
    vm.Telefonos = [];
    //#endregion
    //#region EVENTOS
    vm.ViewTEL = (i) => {
      vm.TEL = angular.copy(vm.TELDATA.data[i])
      vm.VTEL = true
      vm.$apply()
    }
    vm.CrearTEL = () => {
      if (!vm.Datos.$valid) {
        angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus()
      } else {
        vm.TEL.CreatedBy = $rootScope.username.NombreCompleto
        const obj = {
          Telefono: JSON.stringify(vm.TEL)
        }
        TelService.postTel(obj).then(r => {
          if (r.data.data) {
            vm.NTEL = false
            vm.$broadcast('angucomplete-alt:clearInput')
            swal("Enhorabuena!", r.data.data, "success")
          } else {
            swal("Error!", r.data.error, "error")
          }
        })
      }
    }

    vm.UpdateTEL = () => {
      if (!vm.Datos.$valid) {
        angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus()
      } else {
        vm.TEL.ModifiedBy = $rootScope.username.NombreCompleto
        const obj = {
          Telefono: JSON.stringify(vm.TEL)
        }
        TelService.putTel(obj).then(r => {
          if (typeof r.data != 'string') {
            vm.VTEL = false
            vm.$broadcast('angucomplete-alt:clearInput')
            __init__();
            swal("Enhorabuena!", "Se han guardado los cambios", "success")
          } else {
            swal("Error!", r.data, "error")
          }
        })
      }
    }
    //#endregion
    //#region CONSULTAS
    function getTelefonos() {
      vm.LTEL = false;
      TelService.getAllTelefonos().then((r) => {
        vm.TELDATA = {
          data: [],
          aoColumns: [{
              mData: 'TelefonoId'
            },
            {
              mData: 'Lider'
            },
            {
              mData: 'Responsable'
            },
            {
              mData: 'Area'
            },
            {
              mData: 'Sede'
            },
            {
              mData: 'Telefono'
            },
            {
              mData: 'TelefonoAnt'
            },
            {
              mData: 'Estado'
            },
            {
              mData: 'Opciones'
            },
          ],
          "searching": true,
          "iDisplayLength": 25,
          "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": " No hay Items Registrados ",
            "infoFiltered": "(filtro de _MAX_ registros totales)",
            "search": " Filtrar : ",
            "oPaginate": {
              "sPrevious": "Anterior",
              "sNext": "Siguiente"
            }
          },
          "aaSorting": []
        };
        vm.TELDATA.data = SetFormat(r.data);
        vm.LTEL = true;
      });
    }
    //#endregion
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    function SetFormat(lst) {
      for (var i in lst) {
        lst[i].Opciones = '<a class="btn  btn-info btn-xs icon-only white" onclick=\"angular.element(this).scope().ViewTEL(' + i + ')\"><i class="fa fa-eye"></i></a>';
      }
      return lst;
    }
    //</editor-fold>
    function __init__() {
      getTelefonos();
    }
    __init__();
  }
]);