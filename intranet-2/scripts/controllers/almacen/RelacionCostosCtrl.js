app.controller('RelacionCostosCtrl', [
  '$scope',
  '$state',
  '$rootScope',
  '$filter',
  'PedidoAlmacenService',
  'ServicioService',
  'SedeService',
  'ArticuloService',
  'RelacionService',
  function (
    $scope,
    $state,
    $rootScope,
    $filter,
    PedidoAlmacenService,
    ServicioService,
    SedeService,
    ArticuloService,
    RelacionService
  ) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    let vm = $scope
    vm.cargado = false
    vm.simpleTableOptions = {}
    vm.Sedes = []
    vm.Servicios = []
    vm.Servicioslst = []
    vm.RelacionCostos = []
    vm.Relacion = {
      SedeId: '',
      ServicioId: '',
      TipoArticulo: '',
      ArticuloId: '',
      Cantidad: 1,
      DiasConsumo: '',
      UsuarioId: $rootScope.username.UserId,
      CreatedBy: $rootScope.username.NombreCompleto
    }
    vm.URelacion = {
      SedeId: '',
      ServicioId: '',
      ArticuloId: '',
      Cantidad: 1,
      DiasConsumo: '',
      UsuarioId: $rootScope.username.UserId,
      CreatedBy: $rootScope.username.NombreCompleto
    }
    vm.Articulo = {}
    vm.EditRelacion = {
      Cantidad: '',
      DiasConsumo: '',
      Articulo: '',
      UsuarioId: $rootScope.username.UserId,
      CreatedBy: $rootScope.username.NombreCompleto
    }
    vm.Articulos = []
    vm.Relaciones = []
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Botones">
    vm.ChangeSede = function () {
      vm.Relacion.ServicioId = ''
      GetServicio()
    }

    vm.ChangeServicio = function () {
      vm.Articulos = []
      if ($state.current.name != 'almacen.relacion_costos') {
        GetArticulos('Central')
      } else if (
        vm.Relacion.ServicioId == 118 ||
        vm.Relacion.ServicioId == 87
      ) {
        GetArticulos('Almacen')
        GetArticulos('Polivalente')
      } else if (vm.Relacion.ServicioId == 45) {
        GetArticulos('Almacen')
        GetArticulos('Sistemas')
      } else if (
        vm.Relacion.ServicioId == 117 ||
        vm.Relacion.ServicioId == 68
      ) {
        GetArticulos('Almacen')
        GetArticulos('Biomedico')
      } else {
        GetArticulos('Almacen')
      }
    }

    vm.ChangeTipoArticulo = function () {
      vm.cargado = false
      vm.simpleTableOptions = {}

      let Tipo = vm.Relacion.TipoArticulo
      if ($state.current.name == 'farmacia.relacion_costos_central') {
        Tipo = 'Central'
      }
      RelacionService.getRelacionByUsuarioId(
        vm.Relacion.ServicioId,
        vm.Relacion.UsuarioId,
        Tipo
      ).then(function (d) {
        vm.RelacionCostos = d.data
        vm.simpleTableOptions = {
          data: [],
          aoColumns: [
            {
              mData: 'CodigoKrystalos'
            },
            {
              mData: 'NombreKrystalos'
            },
            {
              mData: 'ArticuloId'
            },
            {
              mData: 'Nombre'
            },
            {
              mData: 'Cantidad'
            },
            {
              mData: 'DiasConsumo'
            },
            {
              mData: 'Opciones'
            }
          ],
          searching: true,
          iDisplayLength: 25,
          language: {
            lengthMenu: 'Mostrar _MENU_ registros por página',
            zeroRecords: ' No hay Items Registrados ',
            infoFiltered: '(filtro de _MAX_ registros totales)',
            search: ' Filtrar : ',
            oPaginate: {
              sPrevious: 'Anterior',
              sNext: 'Siguiente'
            }
          },
          aaSorting: []
        }
        vm.simpleTableOptions.data = SetFormat(d.data)
        vm.cargado = true
      })
    }

    vm.ModalCreateRelacion = function () {
      vm.Articulo = ''
      if (!vm.Datos.$valid) {
        angular
          .element("[name='" + vm.Datos.$name + "']")
          .find('.ng-invalid:visible:first')
          .focus()
      } else {
        $('#RelacionModal').modal('show')
      }
    }

    vm.EditItem = function (o) {
      vm.EditRelacion = angular.copy(o)
      $('#ex8_value').val(o.Articulo.Nombre)
    }

    vm.UpdateItem = function (o, i) {
      o.EditarItem = false
      var Relacion = angular.copy(vm.EditRelacion)
      Relacion.Articulo = vm.EditRelacion.Articulo.originalObject
        ? vm.EditRelacion.Articulo.originalObject
        : vm.EditRelacion.Articulo
      Relacion.EditarItem = false
      vm.Relaciones[i] = Relacion
    }

    vm.DeleteItem = function (i) {
      vm.Relaciones.splice(i, 1)
    }

    vm.CreateRelacion = function () {
      
      swal(
        {
          title: '¿Deseas añadir estos articulos a la PLANTILLA?',
          text: 'Nota: una vez añadidos no se podran deshacer los cambios.',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#DD6B55',
          confirmButtonText: 'Añadir',
          cancelButtonText: 'Cancelar',
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function (isConfirm) {
          if (isConfirm) {
            var obj = {
              Relacion: JSON.stringify(vm.Relaciones)
            }
            RelacionService.PostRelacion(obj).then(function (d) {
              if (typeof d.data !== 'string') {
                swal(
                  'Enhorabuena',
                  'Se han guardado los datos satisfactoriamente.',
                  'success'
                )
                vm.ChangeTipoArticulo()
                vm.Relaciones = []
              } else {
                swal('Error', d.data, 'error')
              }
            })
          }
        }
      )
    }

    vm.ChangeEstadoRelacion = function (i, Estado) {
      vm.URelacion = angular.copy(vm.simpleTableOptions.data[i])
      vm.URelacion.Articulo = {
        originalObject: {
          ArticuloId: vm.URelacion.ArticuloId
        }
      }
      vm.URelacion.Estado = Estado
      vm.UpdateRelacion()
    }

    vm.UpdateRelacion = function () {
      vm.URelacion.ModifiedBy = $rootScope.username.NombreCompleto
      var obj = {
        Relacion: JSON.stringify(vm.URelacion)
      }
    
      RelacionService.PutRelacion(obj).then(function (d) {
       
        if (typeof d.data !== 'string') {
          swal(
            'Enhorabuena',
            'Se han guardado los datos satisfactoriamente.',
            'success'
          )
          vm.ChangeTipoArticulo()
          vm.URelacion = {}
          $('#ex10_value').val('')
          $('#UpdateRelacionModal').modal('hide')
        } else {
          swal('Error', d.data, 'error')
        }
      })
    }

    vm.AddItem = function () {
      if (vm.Articulo.originalObject) {
        if (vm.Relacion.Cantidad == 0 || vm.Relacion.Cantidad == '') {
          swal('Error', 'la cantidad no puede ser menor igual a 0', 'error')
        } else if (
          vm.Relacion.DiasConsumo != '7' &&
          vm.Relacion.DiasConsumo != '15' &&
          vm.Relacion.DiasConsumo != '30' &&
          vm.Relacion.DiasConsumo != '60' &&
          vm.Relacion.DiasConsumo != '365'
        ) {
          swal('Error', 'Dias de consumo invalidos', 'error')
        } else {
          vm.Relacion.Articulo = vm.Articulo.originalObject
          vm.Relaciones.push(angular.copy(vm.Relacion))
          vm.Articulo = ''
          $('#ex7_value').val('')
        }
      }
    }

    vm.EditarRelacion = function (i) {
      vm.URelacion = {
        SedeId: '',
        ServicioId: '',
        ArticuloId: '',
        Cantidad: 1,
        DiasConsumo: '',
        UsuarioId: $rootScope.username.UserId,
        CreatedBy: $rootScope.username.NombreCompleto
      }
      vm.URelacion = angular.copy(vm.simpleTableOptions.data[i])
      vm.URelacion.Articulo = {
        originalObject: {
          ArticuloId: vm.URelacion.ArticuloId
        }
      }
      $('#ex10_value').val(vm.URelacion.Nombre)
      vm.$apply()
      $('#UpdateRelacionModal').modal('show')
    }

    vm.Imprimir = function () {
      vm.ToPrint = true
      printDiv()
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="consultas">
    function GetServicio() {
      ServicioService.getServicioBySedeAndUserId(
        vm.Relacion.SedeId,
        $rootScope.username.UserId
      ).then(function (c) {
        vm.Servicios = $filter('orderBy')(c.data, 'Nombre')
        vm.Relacion.ServicioId = vm.Servicios[0].ServicioId
        vm.ChangeTipoArticulo()
      })
    }
    function GetSede() {
      SedeService.getAllSedeByUserId($rootScope.username.UserId)
        .then(function (c) {
          vm.Sedes = c.data
          vm.Relacion.SedeId = c.data[0].SedeId
          //                vm.Servicios = $filter("orderBy")($filter('filter')(vm.Servicioslst, {SedeId: vm.Relacion.SedeId}), "Nombre")
        })
        .then(() => {
          GetServicio()
        })
    }
    function GetArticulos(Tipo) {
      ArticuloService.getAllArticulo(Tipo).then(function (a) {
        a.data.map(articulo => vm.Articulos.push(articulo))
      })
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helpers">

    function printDiv(div) {
      $('#imprimirPedido').print({
        globalStyles: true,
        mediaPrint: false,
        stylesheet: null,
        noPrintSelector: '.no-print',
        iframe: true,
        append: null,
        prepend: null,
        manuallyCopyFormValues: true,
        deferred: $.Deferred(),
        timeout: 1000,
        title: null,
        doctype: '<!doctype html>'
      })

      setTimeout(function () {
        vm.ToPrint = false
        vm.$apply()
      }, 1000)
    }

    function lpad(str, padString, length) {
      while (str.length < length) str = padString + str
      return str
    }

    function SetFormat(lst) {
      for (var i in lst) {
        lst[i].Opciones =
          '<a class="btn  btn-info btn-xs icon-only white"  onclick="angular.element(this).scope().EditarRelacion(' +
          i +
          ')"><i class="fa fa-pencil"></i></a>'
        if (lst[i].Estado === 'Activo') {
          lst[i].Opciones +=
            '<a class="btn  btn-danger btn-xs icon-only white" data-toggle="tooltip" title="Inactivar!" onclick="angular.element(this).scope().ChangeEstadoRelacion(' +
            i +
            ', \'Inactivo\')"><i class="fa fa-times"></i></a>'
        } else {
          lst[i].Opciones +=
            '<a class="btn  btn-success btn-xs icon-only white" data-toggle="tooltip" title="Activar!" onclick="angular.element(this).scope().ChangeEstadoRelacion(' +
            i +
            ', \'Activo\')"><i class="fa fa-check"></i></a>'
        }
      }
      return lst
    }
    //</editor-fold>
    function _init() {
      GetSede()
    }
    _init()
    vm.$on('$destroy', function () {
      vm = null
      $scope = null
    })
  }
])
