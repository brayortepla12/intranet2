app.controller('EntregaTELCtrl', ["$scope", "$rootScope", "$state", "TelService",
  function ($scope, $rootScope, $state, TelService) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    const vm = $scope;
    const DATE = moment();
    const FECHAHOY = DATE.format('YYYY-MM-DDTHH:mm');
    vm.Mes = 'TODOS'
    vm.Year = DATE.format('YYYY');
    vm.Dia = DATE.format('D');
    vm.UltimoDiaMes = Number(DATE.endOf("month").format("DD"));
    vm.SolDATA = [];
    vm.Sol = {};
    vm.Entrega = {};
    vm.NEN = false;
    vm.VEN = false;
    vm.IsLoading = false;
    vm.LSOL = false;
    vm.VSOL = false;
    vm.PPOL = false;
    vm.LineaSeleccionada = "";
    vm.Responsable = "";
    vm.Telefono = "";
    vm.HT = false;
    vm.Historial = [];
    vm.Inventarios = [];
    //</editor-fold>
    //#region (collapsed) SUB Eventos
    vm.GetSolicitudes = () => {
      GetSolicitudes();
    }
    vm.Atras = () => {
      vm.VEN = false;
      vm.NEN = false;
      vm.VSOL = false;
      vm.PPOL = false;
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
    vm.NewEntrega = () => {
      const Fecha = moment();
      vm.NEN = true;
      vm.Entrega = {
        InventarioId: '',
        Fecha: Fecha.format('YYYY-MM-DDTHH:mm'),
        Solicita: vm.Sol.Usuario,
        Cargo: vm.Sol.Cargo,
        Institucion: 'CLINICA LAURA DANIELA',
        Marca: vm.Sol.Marca,
        Modelo: vm.Sol.Modelo,
        IMEI1: "",
        IMEI2: "",
        Color: vm.Sol.Color,
        Ciudad: 'Valledupar',
        Tipo: "Nuevo",
        Descripcion: "Se entrega con cargador, usb y manoslibres.",
        REntregaId: $rootScope.username.PersonaId,
      }
      Fecha.locale('es');
      vm.MES = Fecha.format('MMMM');
      vm.YEAR = Fecha.format('YYYY');
      vm.DIA = Fecha.format('DD');
    }
    vm.Telefonos = [];
    //#endregion
    //#region EVENTOS
    vm.ChangeInventario = () => {
      const index = _.findIndex(vm.Inventarios, {
        InventarioId: Number(vm.Entrega.InventarioId)
      });
      vm.Entrega.Tipo = vm.Inventarios[index].EstadoArticulo;
      vm.Entrega.Marca = vm.Inventarios[index].Marca;
      vm.Entrega.Modelo = vm.Inventarios[index].Modelo;
      vm.Entrega.Color = vm.Inventarios[index].Color;
      vm.Entrega.IMEI1 = vm.Inventarios[index].IMEI1;
      vm.Entrega.IMEI2 = vm.Inventarios[index].IMEI2;
      vm.Entrega.Operador = vm.Inventarios[index].Operador;
    }
    vm.CrearEntrega = () => {
      if (vm.IsLoading) {
        return false;
      }
      if (vm.Entrega.InventarioId.length === 0) {
        swal("Error", "Debes seleccionar un equipo del inventario", "error");
      } else {
        vm.Entrega.CreatedBy = $rootScope.username.NombreCompleto;
        vm.Entrega.SolicitudId = vm.Sol.SolicitudId;
        vm.Entrega.TelefonoId = vm.Sol.TelefonoId;
        const obj = {
          Entrega: JSON.stringify(vm.Entrega)
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
      }).then(() => {
        GetInventario();
        GetHistorialLinea(vm.Sol.TelefonoId);
      })
    }
    vm.ViewEntrega = (i) => {
      const url = window.location.href;
      const arr = url.split("/");
      const result = arr[0] + "//" + arr[2].replace("#", ""); 
      window.open(result + "/Polivalente/api/tel/Tel.php?TPDF=" + vm.SolDATA.data[i].EntregaId, "_blank");
    }
    vm.NotificarEntrega = (i) => {
      swal({
        title: "¿Deseas enviar una notificación?",
        text: "Nota: Cuidado de hacer SPAM!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        confirmButtonText: "Enviar",
        cancelButtonText: "Cancelar",
        closeOnConfirm: true,
        closeOnCancel: true
      }, function (isConfirm) {
        if (isConfirm) {
          TelService.EnviarNotificacion(vm.SolDATA.data[i].EntregaId).then(r => {
            if (typeof r.data === 'string') {
              swal("Informacion", r.data, "info");
            }
          }).catch(error => {
            vm.HT = false;
            console.error(error.stack || error);
          })
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
    function GetInventario() {
      TelService.getInventario().then(d => {
        if (typeof d.data != "string" && d.data.length > 0) {
          vm.Inventarios = d.data;
        }
      })
    }

    function GetSolicitudes() {
      vm.LSOL = false;
      TelService.getSolicitudes(vm.Dia, vm.Mes, vm.Year).then((d) => {
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
        vm.LSOL = true;
      });
    }
    //#endregion
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
        if (lst[i].Estado === "Activo") {
          lst[i].Opciones = '<a class="btn  btn-info btn-xs icon-only white" data-toggle="tooltip" title="Ver solicitud" onclick=\"angular.element(this).scope().ViewSolicitud(' + i + ')\"><i class="fa fa-eye"></i></a>';
        } else if (lst[i].Estado === "Entregada") {
          lst[i].Opciones = '<a class="btn  btn-info btn-xs icon-only white" data-toggle="tooltip" title="Ver entrega" onclick=\"angular.element(this).scope().ViewEntrega(' + i + ')\"><i class="fa fa-file"></i></a>';
          lst[i].Opciones += '<a class="btn  btn-warning btn-xs icon-only white" data-toggle="tooltip" title="Reenviar email entrega" onclick=\"angular.element(this).scope().NotificarEntrega(' + i + ')\"><i class="fa fa-paper-plane"></i></a>';
        } else if (lst[i].Estado === "Finalizada") {
          lst[i].Estado = 'Firmada'
          lst[i].Opciones = '<a class="btn  btn-info btn-xs icon-only white" data-toggle="tooltip" title="Ver entrega" onclick=\"angular.element(this).scope().ViewEntrega(' + i + ')\"><i class="fa fa-file"></i></a>';
        }
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