app.controller('ListarAllReportesCtrl', ["$scope", "$rootScope", "ReporteService", "$filter", "ServicioService", "SedeService",
  function ($scope, $rootScope, ReporteService, $filter, ServicioService, SedeService) {
    let vm = $scope;
    vm.simpleTableOptions = null;
    vm.SedeId = null;
    vm.ServicioId = null;
    vm.Servicios = [];
    vm.Servicios2 = [];
    let DATE = moment();
    vm.Estado = "TODOS";
    vm.Mes = 'TODOS';
    vm.Year = DATE.format('YYYY');
    vm.Dia = DATE.format('D');
    vm.UltimoDiaMes = Number(DATE.endOf("month").format("DD"));
    _init();
    //<editor-fold defaultstate="collapsed" desc="Botones">
    vm.DeleteReporte = function (i) {
      swal({
        title: "¿Deseas ELIMINAR este REPORTE?",
        text: "Nota: Este paso no se puede retroceder!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "ELIMINAR!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
      }, function (isConfirm) {
        if (isConfirm) {
          swal("Cancelado", vm.simpleTableOptions.data[i].ReporteId, "error");
          vm.simpleTableOptions.data[i].ModifiedBy = $rootScope.username.NombreUsuario;
          var obj = {
            Reporte: JSON.stringify([vm.simpleTableOptions.data[i]]),
            UserId: $rootScope.username.NombreUsuario
          };
          ReporteService.DeleteReporte(obj).then(function (d) {
            if (typeof d.data !== 'string') {
              swal("Enhorabuena!", "Se ha ELIMINADO este reporte.", "success");
              vm.cargado = false;
              vm.simpleTableOptions = null;
              vm.Servicios = [];
              _init();
            } else {
              swal("Error", d.data, "error");
            }
          });
        }
      });
    };
    vm.ImprimirBySERVICIO = function () {
      GetSede();
      $('#ServicioModal').modal('show');
    };
    vm.ReenviarEmail = function (i) {
      swal({
        title: "¿Deseas Reenviar el correo de notificación?",
        text: "Nota: Cuidado de hacer SPAM!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Reenviar!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
      }, function (isConfirm) {
        if (isConfirm) {
          ReporteService.SendEmail(vm.simpleTableOptions.data[i].ReporteId).then(function (d) {
            if (typeof d.data !== 'string') {
              swal("Enhorabuena!", "Se ha enviado el reporte para que el usuario pueda firmarlo.", "success");
            } else {
              swal("Error", d.data, "error");
            }
          });
        } else {
          swal("Cancelado", "No se ha enviado el correo... :)", "error");
        }
      });
    };
    vm.AutoFirmarTODO = function () {
      swal({
        title: "¿Deseas FIRMAR TODO?",
        text: "Nota: Cuidado de hacer SPAM!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "FIRMAR!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: false
      }, function (isConfirm) {
        if (isConfirm) {

          ReporteService.AutoFirmar($rootScope.username.UserId).then(function (d) {
            console.log(d.data);
            if (typeof d.data !== 'string') {
              _init();
              swal("Enhorabuena!", "Se han firmado todos los reportes.", "success");
            } else {
              swal("Error", d.data, "error");
            }
          });
        } else {
          swal("Cancelado", "Tranquil@ no hubo ningun cambio... :)", "error");
        }
      });
    };
    vm.ChangeSede = function () {
      vm.Servicios2 = angular.copy($filter("filter")(vm.Servicios, {
        SedeId: parseInt(vm.SedeId)
      }, true));
      vm.ServicioId = null;
    };
    vm.GetREPORTES = () => {
      GetReportes()
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Consulta">
    function GetServicio() {
      ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (c) {
        vm.Servicios = $filter("orderBy")(angular.copy(c.data), "Nombre");
        vm.ChangeSede();
      });
    }

    function GetSede() {
      SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
        vm.Sedes = $filter("orderBy")(c.data, "Nombre");
        vm.SedeId = vm.Sedes[0].SedeId;
        GetServicio();
      });
    }

    function GetReportes() {
      vm.cargado = false;
      ReporteService.GetAllReportes($rootScope.username.UserId, vm.Dia, vm.Mes, vm.Year).then(function (d) {
        vm.simpleTableOptions = {
          data: [],
          aoColumns: [{
              mData: 'ReporteId'
            },
            {
              mData: 'Sede'
            },
            {
              mData: 'Servicio'
            },
            {
              mData: 'Ubicacion'
            },
            {
              mData: 'TipoServicio'
            },
            {
              mData: 'Solicitante'
            },
            {
              mData: 'Equipo'
            },
            {
              mData: 'Serie'
            },
            {
              mData: 'CreatedBy'
            },
            {
              mData: 'CreatedAt'
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
        vm.simpleTableOptions.data = SetFormat(d.data);
        vm.cargado = true;
      });
    }

    function _init() {
      GetReportes();
    }
    //</editor-fold>
    // #region Helpers
    function SetFormat(lst) {
      for (var i in lst) {
        if ($rootScope.username.IsStaff == '1') {
          lst[i].Opciones = '';
          if (lst[i].TipoServicio !== 'INFRAESTRUCTURA'){
            lst[i].Opciones = '<a class="btn  btn-primary btn-xs icon-only white" href="/Polivalente#/polivalente/ficha_tecnica/' + lst[i].HojaVidaId + '" target="_blank"><i class="fa fa-stethoscope"></i></a>'
          }
          lst[i].Opciones += '<a class="btn  btn-info btn-xs icon-only white" href="/Polivalente#/polivalente/reporte_servicio/' + lst[i].ReporteId + '" target="_blank"><i class="fa fa-pencil-square-o"></i></a>' +
            '<a class="btn  btn-danger btn-xs icon-only white" onclick=\"angular.element(this).scope().DeleteReporte(' + i + ')\" target="_blank"><i class="fa fa-trash"></i></a>' +
            '<a class="btn  btn-success btn-xs icon-only white" href="/Polivalente/#/polivalente/ver_reporte/' + lst[i].ReporteId + '" target="_blank"><i class="fa fa-file-text-o"></i></a>';
        } else {
          lst[i].Opciones = '<a class="btn  btn-success btn-xs icon-only white" href="/Polivalente/#/polivalente/ver_reporte/' + lst[i].ReporteId + '" target="_blank"><i class="fa fa-file-text-o"></i></a>';
        }

        lst[i].TotalRepuesto = $filter('currency')(lst[i].TotalRepuesto);

        if (lst[i].Estado === 'Borrador') {
          lst[i].Opciones += '<a class="btn  btn-warning btn-xs icon-only white" onclick=\"angular.element(this).scope().ReenviarEmail(' + i + ')\"><i class="fa fa-paper-plane-o"></i></a>'
        }
      }
      return lst;
    }
    // #endregion
  }
]);