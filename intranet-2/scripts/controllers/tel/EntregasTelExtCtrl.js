app.controller('EntregasTelExtCtrl', ["$scope", "$rootScope", "$state", "TelService",
  function ($scope, $rootScope, $state, TelService) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    const vm = $scope
    vm.VEN = false
    vm.LSOL = false
    vm.Loading = false;
    vm.SolDATA = []
    // #region CONSULTAS
    function GetSolicitudesByToken(token) {
      vm.LSOL = false
      TelService.GetSolicitudesByToken(token).then((d) => {
        vm.SolDATA = {
          data: [],
          aoColumns: [{
              mData: 'SolicitudId'
            },
            {
              mData: 'Fecha'
            },
            {
              mData: 'Usuario'
            },
            {
              mData: 'Telefono'
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
        vm.SolDATA.data = SetFormat(d.data);
        if (vm.SolDATA.data.length === 1) {
          vm.ViewEntrega(0)
          vm.LSOL = true;
        }else {
          vm.LSOL = true;
        }
      });
    }
    // #endregion
    // #region EVENTOS
    vm.FirmarEntrega = () => {
      swal({
      title: "¿Aceptas nuestras POLITICAS MANEJO DE PLAN CORPORATIVOS?",
      text: "<a href='/Polivalente/#/POLITICAS' target='_blank'>VER POLITICAS</a> <br><input type='checkbox' id='POLITICAS'/> <label for='POLITICAS'>Acepto las politicas</label> ",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-primary",
      confirmButtonText: "SI",
      cancelButtonText: "NO",
      closeOnConfirm: false,
      closeOnCancel: true,
      html: true,
      }, function (isConfirm) {
          if (isConfirm && !vm.Loading) {
              const politicas = $("#POLITICAS").is(':checked')
              if (politicas) {
                // firmamos 
                vm.Loading = true;
                const obj = {
                  FirmarEntrega: JSON.stringify({
                    EntregaId: vm.Entrega.EntregaId,
                    SolicitudId: vm.Entrega.SolicitudId
                  })
                }
                TelService.putTel(obj).then(d => {
                  vm.Loading = false;
                  if (typeof d.data !== "string" && d.data.length > 0) {
                    vm.VEN = false
                    swal("Enhorabuena!", "Se han guardado los cambios", "success")
                    __init__()
                  }
                }).catch(error => {
                  vm.Loading = false;
                })
              } else {
                swal("Error", "Debes aceptar las politicas", "error");
              }
          }
      })
    }
    vm.ViewEntrega = (i) => {
      TelService.getEntregaBySolicitudId(vm.SolDATA.data[i].SolicitudId).then(d => {
        if (typeof d.data !== "string" && d.data.length > 0) {
          vm.Entrega = d.data[0]
          const Fecha = moment(vm.Entrega.Fecha);
          Fecha.locale('es');
          vm.MES = Fecha.format('MMMM');
          vm.YEAR = Fecha.format('YYYY');
          vm.DIA = Fecha.format('DD');
          vm.VEN = true;
          vm.LSOL = false
        }
      })
    }
    vm.Imprimir = (id) => {
      if (id === 'H2') {
        vm.PPOL = true;
      }else {
        vm.PPOL = false;
      }
      setTimeout(function () {
        printDiv(id);
      }, 1000);
    }
    // #endregion

    vm.Atras = () => {
      vm.VEN = false
      vm.LSOL = true
    }
    
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    function printDiv(id){
      $("#" + id).print({
        globalStyles: false,
        mediaPrint: false,
        stylesheet: vm.PPOL ? "/intranet-2/public_html/styles/TPoliticas.css" : "/intranet-2/public_html/styles/TEL.css",
        noPrintSelector: ".no-print",
        iframe: true,
        append: null,
        prepend: null,
        manuallyCopyFormValues: true,
        deferred: $.Deferred(),
        timeout: 2500,
        title: null,
        doctype: '<!doctype html>'
      });
  
      setTimeout(function () {
        $scope.$apply();
      }, 1000);
    }
    function SetFormat(lst) {
      for (var i in lst) {
        lst[i].Opciones = '<a class="btn  btn-info btn-xs icon-only white" data-toggle="tooltip" title="Ver hoja entrega" onclick=\"angular.element(this).scope().ViewEntrega(' + i + ')\"><i class="fa fa-file"></i></a>';
      }
      return lst;
    }
    //</editor-fold>
    function __init__() {
      const token = $state.params.token
      GetSolicitudesByToken(token)
    }
    __init__()
  }
]);