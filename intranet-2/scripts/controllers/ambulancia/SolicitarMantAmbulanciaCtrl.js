'use strict';
app.controller('SolicitarMantAmbulanciaCtrl', ["$scope", "$rootScope", "$filter", "ReporteAmbulanciaService",
  "SolicitudAmbulanciaService",
  "DetalleAmbulanciaService", "HojaVidaAmbulanciaService", "SedeService",
  function ($scope, $rootScope, $filter, ReporteAmbulanciaService,
    SolicitudAmbulanciaService,
    DetalleAmbulanciaService, HojaVidaAmbulanciaService, SedeService) {
    //<editor-fold defaultstate="collapsed" desc="inicializar variables">
    let vm = $scope;
    vm.Placa = ''
    vm.TipoSolicitud = "";
    vm.CargandoBandera = false;
    vm.BSaveReport = false;
    vm.Mes = 'TODOS'
    vm.Year = moment().format('YYYY');
    vm.Estado = 'Activo';
    vm.VerInformacion = '';
    vm.cargado = false;
    vm.UpdateBandera = false;
    vm.BanderaReporte = false;
    vm.BnFR = false;
    vm.simpleTableOptions = {};
    vm.labels = {
      "itemsSelected": "Seleccionados",
      "selectAll": "Seleccionar Todo",
      "unselectAll": "Deseleccionar Todo",
      "search": "Buscar",
      "select": "Seleccionar"
    };
    vm.Reporte = {
      SedeId: null,
      HojaVidaId: null,
      Fecha: moment().format('YYYY-MM-DD'),
      Descripcion: "",
      Notas: "",
      Km: "",
      LastKm: "",
      Detalles: [],
      CreatedBy: $rootScope.username.NombreUsuario
    };
    vm.MinKM = 0;
    vm.ItemsCronograma = [];
    vm.Sistemas = [];
    vm.Solicituds = [];
    vm.FechaActual = new Date();
    vm.Sedes = [];
    vm.Equipos = [];
    vm.Detalles = [];
    vm.DetallesSeleccionados = [];
    vm.SedeId = '';
    vm.Solicitud = {
      SedeId: null,
      HojaVidaId: null,
      Fecha: "",
      TipoSolicitud: "PREVENTIVO",
      Descripcion: "",
      Notas: "",
      Km: "",
      LastKm: "",
      Detalles: [],
      CreatedBy: $rootScope.username.NombreUsuario
    };
    // inicializamos las variables en _init()
    _init();
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Consultas">
    function GetSede() {
      SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
        vm.Sedes = $filter("orderBy")(c.data, "Nombre");
        vm.SedeId = vm.Sedes[0].SedeId;
        if (vm.Sedes.length === 1) {
          vm.Reporte.SedeId = vm.Sedes[0].SedeId;
        }
      });
    }

    function GetSolicituds(SedeId) {
      vm.cargado = false;
      SolicitudAmbulanciaService.getSolicitudes(vm.Year, vm.Mes, vm.Estado, vm.Placa).then(function (c) {
        vm.simpleTableOptions = {
          data: [],
          aoColumns: [{
              mData: 'SolicitudMantenimientoId'
            },
            {
              mData: 'Fecha'
            },
            {
              mData: 'TipoSolicitud'
            },
            {
              mData: 'Placa'
            },
            {
              mData: 'Descripcion'
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
        vm.simpleTableOptions.data = SetFormat(c.data);
        vm.cargado = true;
      });
    }

    function GetDetalles() {
      DetalleAmbulanciaService.getall().then(function (c) {
        vm.Detalles = c.data;
      });
    }

    function GuardarSolicitud() {
      if (!vm.CargandoBandera) {
        vm.CargandoBandera = true;
        vm.Solicitud.CreatedBy = $rootScope.username.NombreCompleto;
        var data = {
          Solicitud: JSON.stringify(vm.Solicitud)
        };
        SolicitudAmbulanciaService.postSolicitud(data).then(function (d) {
          if (typeof d.data === "string") {
            swal("Hubo un Error", d.data, "error");
          } else {
            swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
            vm.Solicitud = {
              SedeId: null,
              HojaVidaId: null,
              Fecha: "",
              TipoSolicitud: "PREVENTIVO",
              Descripcion: "",
              Notas: "",
              Km: "",
              LastKm: "",
              Detalles: [],
              CreatedBy: $rootScope.username.NombreUsuario
            };
            vm.DetallesSeleccionados = [];
            vm.Solicituds = [];
            vm.Equipos = [];
            $('#SolicitudModal').modal('hide');
            GetSolicituds(vm.SedeId);
            //                    _init();
          }
          vm.CargandoBandera = false;
        }, function (e) {
          swal("Hubo un Error", e, "error");
          vm.CargandoBandera = false;
        });
      }
    }

    function GuardarReporte() {
      vm.Reporte.Detalles = JSON.stringify(vm.DetallesSeleccionados);
      vm.Reporte.CreatedBy = $rootScope.username.NombreCompleto;
      var data = {
        Reporte: JSON.stringify(vm.Reporte)
      };
      ReporteAmbulanciaService.postReporte(data).then(function (d) {
        if (typeof d.data === "string") {
          swal("Hubo un Error", d.data, "error");
        } else {
          swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
          vm.Reporte = {
            SedeId: null,
            HojaVidaId: null,
            Fecha: "",
            SolicitudMantenimientoId: null,
            Descripcion: "",
            Notas: "",
            Km: "",
            LastKm: "",
            Detalles: [],
            CreatedBy: $rootScope.username.NombreUsuario
          };
          vm.DetallesSeleccionados = [];
          vm.BanderaReporte = false;
          vm.BSaveReport = false;
          $("#VerFacturaModal").modal('hide');
          GetSolicituds();
        }
      }, function (e) {
        swal("Hubo un Error", e, "error");
        vm.BSaveReport = false;
      });
    }

    function GetFactura(i) {
      SolicitudAmbulanciaService.GetFacturaBySolicitudId(vm.simpleTableOptions.data[i].SolicitudMantenimientoId).then((d) => {
        console.log(d.data);
        vm.VerInformacion = vm.simpleTableOptions.data[i].Descripcion;
        vm.VerFactura = d.data;
        vm.TipoSolicitud = vm.simpleTableOptions.data[i].TipoSolicitud;
        vm.Reporte = {
          SedeId: vm.simpleTableOptions.data[i].SedeId,
          HojaVidaId: vm.simpleTableOptions.data[i].HojaVidaId,
          Fecha: moment().format('YYYY-MM-DD'),
          SolicitudMantenimientoId: vm.simpleTableOptions.data[i].SolicitudMantenimientoId,
          Descripcion: "",
          TipoMantenimiento: "PREVENTIVO",
          Notas: "",
          Km: "",
          LastKm: "",
          Detalles: [],
          CreatedBy: $rootScope.username.NombreUsuario
        };
        $("#VerFacturaModal").modal('show');
      });
    }

    function GetReporteBySolicitudMant(i) {
      SolicitudAmbulanciaService.GetReporteBySolicitudId(vm.simpleTableOptions.data[i].SolicitudMantenimientoId).then((d) => {
        vm.Reporte = d.data;
      });
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">
    vm.FiltrarPorPlaca = () => {
      if (vm.Placa.length >= 3) {
        vm.BuscarSolicituds()
      }
    }
    vm.OpenModal = () => {
      vm.Equipos = [];
      vm.Solicitud = {
        HojaVidaId: null,
        Fecha: moment().format('YYYY-MM-DD'),
        Descripcion: "",
        TipoSolicitud: "PREVENTIVO",
        CreatedBy: $rootScope.username.NombreUsuario
      };
      $("#SolicitudModal").modal('show');
    };
    vm.ChangeSede = () => {
      HojaVidaAmbulanciaService.GetHojaVidaSedeId(vm.Solicitud.SedeId).then((d) => {
        console.log(d.data);
        vm.Equipos = d.data;
      });
    };
    vm.BuscarSolicituds = () => {
      GetSolicituds(vm.SedeId);
    };
    vm.EditarSolicitud = (SolicitudId) => {
      SolicitudAmbulanciaService.GetSolicitudById(SolicitudId).then((d) => {
        vm.ChangeSede();
        console.log(d.data);
        vm.Solicitud = d.data[0];
        vm.DetallesSeleccionados = vm.Solicitud.Detalles;
        vm.UpdateBandera = true;
        $("#SolicitudModal").modal('show');
      });
    };
    vm.ChangeEquipo = function () {
      GetLasKM(vm.Solicitud.HojaVidaId);
    };
    vm.GenerarReporte = () => {
      vm.BanderaReporte = true;
    };
    vm.Delete = function (o) {
      o.ModifiedBy = $rootScope.username.NombreCompleto;
      swal({
          title: "¿Deseas eliminar este cronograma?",
          text: "NOTA: este paso no se puede deshacer.",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Eliminar!",
          closeOnConfirm: false
        },
        function () {
          var data = {
            Solicitud: JSON.stringify([o])
          };
          SolicitudAmbulanciaService.DeleteSolicitud(data).then(function (d) {
            if (typeof d.data === "string") {
              swal("Hubo un Error", d.data, "error");
            } else {
              swal("Enhorabuena!!", "Se ha eliminado este cronograma correctamente", "success");
              _init();
            }
          }, function (e) {
            swal("Hubo un Error", e, "error");
          });
        });
    };
    vm.GuardarSolicitud = function () {
      //            console.log(vm.Solicitud);
      GuardarSolicitud();
    };
    vm.ActualizarSolicitud = () => {
      vm.Solicitud.ModifiedBy = $rootScope.username.NombreCompleto;
      vm.Solicitud.Detalles = JSON.stringify(vm.DetallesSeleccionados);
      var obj = {
        Solicitud: JSON.stringify(vm.Solicitud)
      };
      SolicitudAmbulanciaService.PutSolicitud(obj).then(function (d) {
        if (typeof d.data === "string") {
          swal("Hubo un Error", d.data, "error");
        } else {
          swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
          vm.Solicitud = {
            SedeId: null,
            HojaVidaId: null,
            Fecha: "",
            TipoSolicitud: "PREVENTIVO",
            Descripcion: "",
            Notas: "",
            Km: "",
            LastKm: "",
            Detalles: [],
            CreatedBy: $rootScope.username.NombreUsuario
          };
          vm.DetallesSeleccionados = [];
          vm.Solicituds = [];
          vm.Equipos = [];
          $('#SolicitudModal').modal('hide');
          GetSolicituds(vm.SedeId);
        }
      }, function (e) {
        swal("Hubo un Error", e, "error");
      });
    };
    vm.Imprimir = function () {
      $.print("#printable");
    };
    vm.GetFacturaWithReporte = (i) => {
      vm.BnFR = true;
      GetFactura(i);
      GetReporteBySolicitudMant(i);
    };
    vm.GetFactura = (i) => {
      vm.BnFR = false;
      GetFactura(i);
    };
    vm.GetSuma = (lst) => {
      let Total = 0;
      for (let i in lst) {
        Total += lst[i].Precio * lst[i].Cantidad;
      }
      return Total;
    };
    vm.GuardarReporte = function () {
      if (!vm.BSaveReport) {
        vm.BSaveReport = false;
        GuardarReporte();
      }
    };
    //</editor-fold>

    function SetFormat(lst) {
      for (var i in lst) {
        if (lst[i].Estado == 'Activo') {
          lst[i].Opciones = '';
        } else if (lst[i].Estado == 'Vinculado') {
          lst[i].Opciones = '<a class="btn btn-default btn-xs icon-only white" data-toggle="tooltip" title="Ver factura y reporte" onclick=\"angular.element(this).scope().GetFacturaWithReporte(' + i + ')\"' +
            '><i class="fa fa-eye"></i></a>';
        } else {
          lst[i].Opciones = '<a class="btn btn-primary btn-xs icon-only white" data-toggle="tooltip" title="Ver factura" onclick=\"angular.element(this).scope().GetFactura(' + i + ')\"' +
            '><i class="fa fa-eye"></i></a>';
          //                            '<a class="btn btn-warning btn-xs icon-only white" data-toggle="tooltip" title="Crear reporte" onclick=\"angular.element(this).scope().CrearReporte(' + i + ')\"' +
          //                            '><i class="fa fa-wrench"></i></a>';
        }

      }
      return lst;
    }

    function _init() {
      GetSede();
      GetSolicituds();
      GetDetalles();
    }


  }
]);