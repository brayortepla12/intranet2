app.controller('InventarioTELCtrl', ["$scope", "$rootScope", "$state", "TelService",
  function ($scope, $rootScope, $state, TelService) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    const vm = $scope;
    vm.InvDATA = [];
    vm.Inv = {
      Marca: '',
      Modelo: '',
      Operador: '',
      Color: '',
      IMEI1: '',
      IMEI2: '',
      Stock: 1,
      EstadoArticulo: 'Nuevo',
    }
    vm.NINV = false;
    vm.VINV = false;
    vm.IsLoading = false;
    vm.LINV = false;
    //</editor-fold>
    //#region (collapsed) SUB Eventos
    vm.GetInventarios = () => {
      GetInventarios();
    }
    vm.Atras = () => {
      vm.NINV = false
      vm.VINV = false
      vm.Inv = {
        Marca: '',
        Modelo: '',
        Operador: '',
        Color: '',
        IMEI1: '',
        IMEI2: '',
        Stock: 1,
        EstadoArticulo: 'Nuevo',
      }
    }
    vm.NewINV = () => {
      vm.NINV = true;
      vm.Inv = {
        Marca: '',
        Modelo: '',
        Operador: '',
        Color: '',
        IMEI1: '',
        IMEI2: '',
        Stock: 1,
        EstadoArticulo: 'Nuevo',
      }
    }
    vm.Telefonos = [];
    //#endregion
    //#region EVENTOS
    vm.ViewInv = (i) => {
      console.log(i)
      vm.Inv = angular.copy(vm.InvDATA.data[i])
      vm.VINV = true
      vm.$apply()
    }
    vm.CrearINV = () => {
      if (!vm.Datos.$valid) {
        angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus()
      } else {
        vm.Inv.CreatedBy = $rootScope.username.NombreCompleto
        const obj = {
          Inventario: JSON.stringify(vm.Inv)
        }
        TelService.postTel(obj).then(r => {
          if (typeof r.data != 'string') {
            vm.NINV = false
            swal("Enhorabuena!", "Se han guardado los datos", "success")
          } else {
            swal("Error!", r.data, "error")
          }
        })
      }
    }

    vm.UpdateINV = () => {
      if (!vm.Datos.$valid) {
        angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus()
      } else {
        vm.Inv.ModifiedBy = $rootScope.username.NombreCompleto
        const obj = {
          Inventario: JSON.stringify(vm.Inv)
        }
        TelService.putTel(obj).then(r => {
          if (typeof r.data != 'string') {
            vm.VINV = false
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
    function GetInventarios() {
      vm.LINV = false;
      TelService.getInventario().then((r) => {
        vm.InvDATA = {
          data: [],
          aoColumns: [{
              mData: 'InventarioId'
            },
            {
              mData: 'Marca'
            },
            {
              mData: 'Modelo'
            },
            {
              mData: 'Operador'
            },
            {
              mData: 'Color'
            },
            {
              mData: 'IMEI1'
            },
            {
              mData: 'IMEI2'
            },
            {
              mData: 'Stock'
            },
            {
              mData: 'EstadoArticulo'
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
        vm.InvDATA.data = SetFormat(r.data);
        vm.LINV = true;
      });
    }
    //#endregion
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    function SetFormat(lst) {
      for (var i in lst) {
        lst[i].Opciones = '<a class="btn  btn-info btn-xs icon-only white" onclick=\"angular.element(this).scope().ViewInv(' + i + ')\"><i class="fa fa-eye"></i></a>';
      }
      return lst;
    }
    //</editor-fold>
    function __init__() {
      GetInventarios();
    }
    __init__();
  }
]);