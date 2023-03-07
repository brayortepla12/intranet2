app.controller('SolicitudTELCtrl', ["$scope", "$rootScope", "$state", "TelService",
  function ($scope, $rootScope, $state, TelService) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    const vm = $scope;
    const DATE = moment();
    const FECHAHOY = DATE.format('YYYY-MM-DDTHH:mm');
    vm.Mes = DATE.format('M');
    vm.Year = DATE.format('YYYY');
    vm.Dia = DATE.format('D');
    vm.UltimoDiaMes = Number(DATE.endOf("month").format("DD"));
    vm.SolDATA = [];
    vm.Sol = {
      Fecha: FECHAHOY,
      Telefono: '',
      DescripcionDano: '',
      Fotos: null
    }
    vm.NSOL = false;
    vm.IsLoading = false;
    vm.LSOL = false;
    vm.VSOL = false;
    vm.LineaSeleccionada = "";
    vm.Responsable = "";
    vm.Telefono = "";
    vm.HT = false;
    vm.Historial = [];
    //</editor-fold>
    //#region (collapsed) SUB Eventos
    vm.ChangeLinea = () => {
      GetHistorialLinea(vm.Telefonos[vm.LineaSeleccionada].TelefonoId)
      vm.Sol.TelefonoId = vm.Telefonos[vm.LineaSeleccionada].TelefonoId;
      vm.Responsable = vm.Telefonos[vm.LineaSeleccionada].Responsable;
      vm.Telefono = vm.Telefonos[vm.LineaSeleccionada].Telefono;
    }
    vm.GetSolicitudes = () => {
      GetSolicitudes();
    }
    vm.Atras = () => {
      vm.NSOL = false;
      vm.VSOL = false;
      vm.LineaSeleccionada = "";
      vm.Responsable = "";
      vm.Telefono = "";
      vm.Sol = {
        Fecha: DATE.format('YYYY-MM-DDTHH:mm'),
        Telefono: '',
        DescripcionDano: '',
        Fotos: null
      }
    }
    vm.NewSOL = () => {
      vm.NSOL = true;
      vm.Sol = {
        Fecha: FECHAHOY,
        Telefono: '',
        DescripcionDano: '',
        Fotos: null
      }
      if (vm.Telefonos.length === 0) {
        GetTelefonos();
      }
    }
    vm.Telefonos = [];
    //#endregion
    //#region EVENTOS
    vm.CrearSOL = () => {
      if (vm.IsLoading) {
        return false;
      }
      if (vm.Sol.DescripcionDano.length === 0) {
        swal("Error", "Debes añadir una descripción", "error");
      } else if (vm.LineaSeleccionada.length === 0) {
        swal("Error", "Debes seleccionar una linea", "error");
      } else {
        vm.Sol.USolicitaId = $rootScope.username.UserId;
        vm.Sol.CreatedBy = $rootScope.username.NombreCompleto;
        vm.Sol.RSolicitaId = $rootScope.username.PersonaId;
        const obj = {
          Solicitud: JSON.stringify(vm.Sol)
        }
        vm.IsLoading = true;
        TelService.postTel(obj).then(d => {
          vm.IsLoading = false;
          if (typeof d.data === "string") {
            swal("Error", d.data, "error");
          } else {
            swal("Enhorabuena!", "Se han guardado los datos con exito", "success");
            vm.Atras();
            GetSolicitudes();
          }
        }).catch(error => {
          vm.IsLoading = false;
          console.error(error.stack || error);
        });
      }
    }
    vm.ViewSolicitud = (i) => {
      TelService.getSolicitudesBySolicitudId(vm.SolDATA.data[i].SolicitudId).then(d => {
        if (typeof d.data !== "string" && d.data.length > 0) {
          vm.Sol = d.data[0];
          vm.VSOL = true;
        }
      })
    }
    //#endregion
    //#region CONSULTAS
    function GetHistorialLinea(TelefonoId){
      vm.HT = false;
      TelService.getHistorial(TelefonoId).then(d => {
        if (typeof d.data !== "string" && d.data.length > 0) {
          vm.Historial = d.data;
          vm.HT = true;
        }
      }).catch(error => {
        vm.HT = false;
        console.error(error.stack || error);
      })
    }
    function GetTelefonos() {
      TelService.getTelefonos($rootScope.username.PersonaId).then(d => {
        if (typeof d.data != "string" && d.data.length > 0) {
          vm.Telefonos = d.data;
        }
      })
    }

    function GetSolicitudes() {
      vm.LSOL = false;
      TelService.getSolicitudesByUsuarioId(vm.Dia, vm.Mes, vm.Year, $rootScope.username.UserId).then((d) => {
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
              mData: 'PosEsperaTel'
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
        vm.LSOL = true;
      });
    }
    //#endregion
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    function SetFormat(lst) {
      for (var i in lst) {
        lst[i].Opciones = '<a class="btn  btn-info btn-xs icon-only white" onclick=\"angular.element(this).scope().ViewSolicitud(' + i + ')\"><i class="fa fa-eye"></i></a>';

      }
      return lst;
    }
    //</editor-fold>
    function __init__() {
      GetSolicitudes();
    }
    __init__();
  }
]);