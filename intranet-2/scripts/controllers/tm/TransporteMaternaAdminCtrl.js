app.controller('TransporteMaternaAdminCtrl', ["$scope", "$rootScope", "TmEventoService", "MaternaService", "DepartamentoService", "MunicipioService", "TarifaService",
  "EmpresaService", "EncabezadoService",
  function ($scope, $rootScope, TmEventoService, MaternaService, DepartamentoService, MunicipioService, TarifaService, EmpresaService, EncabezadoService) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    const fechaHoy = moment().format().split('T')[0]
    let popup = {}
    let vm = $scope
    vm.TipoEvento = "Solicitado"
    vm.CargandoBandera = false
    vm.ToPrint = false
    vm.cargado = false
    vm.Impresion = false
    vm.EventosAnteriores = []
    vm.UEvento = {
      Nombres: "",
      Documento: "",
      Telefono: "",
      DepartamentoId: 11,
      MunicipioId: "",
      Procedimiento: "",
      Comentario: "",
      FechaParto: "",
      TipoEvento: "Control Prenatal",
      TipoTransporte: "Particular",
      Detalles: [],
      Acompanante: false,
      MaternaId: null,
      ModifiedBy: $rootScope.username.NombreCompleto
    }
    vm.Departamentos = []
    vm.Municipios = []
    vm.TarifaId = ""
    vm.Tarifas = []
    vm.Evento = {
      Nombres: "",
      Documento: "",
      Telefono: "",
      DepartamentoId: 11,
      MunicipioId: "",
      Procedimiento: "",
      Comentario: "",
      TipoEvento: "Control Prenatal",
      TipoTransporte: "Particular",
      FechaParto: "",
      Detalles: [],
      Acompanante: false,
      MaternaId: null,
      CreatedBy: $rootScope.username.NombreCompleto
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Botones">
    vm.VerTarifas = () => {
      const url = '/intranet-2/#/admin_tarifas'
      const strWindowFeatures = "location=no,height=1000,width=1400,scrollbars=yes,status=yes"
      popup = window.open(url, 'legalizar Solicitud viatico', strWindowFeatures)
    }
    vm.EliminarEvento = (EventoId) => {
      swal({
        title: "¿Deseas ELIMINAR este evento?",
        text: "Nota: Este paso no se puede deshacer!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "ELIMINAR!",
        cancelButtonText: "Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
      }, function (isConfirm) {
        if (isConfirm) {
          let obj = {
            EventoId: EventoId
          }
          TmEventoService.deleteTmEvento(obj).then(function (d) {
            if (typeof d.data !== 'string') {
              swal("Enhorabuena!", "Se ha ELIMINADO este evento.", "success")
              GetEventos()
            } else {
              swal("Error", d.data, "error")
            }
          })
        }
      })
    }
    vm.BuscarEventos = () => {
      GetEventos()
    }
    vm.ConsultarTarifaByMunicipio = () => {
      GetTarifaByMunicipio()
    }
    vm.Reset = () => {
      Reset()
    }
    vm.GestionarImprimir = (EventoId, o) => {
      let obj = {
        GestionarEventoId: EventoId,
        ModifiedBy: $rootScope.username.NombreCompleto,
      }
      TmEventoService.putTmEvento(obj).then(function (d) {
        if (typeof d.data != "string") {
          swal("Enhorabuena", "Se ha actualizado el evento con exito", "success")
          o.Estado = "Gestionado"
          setTimeout(() => {
            vm.Imprimir()
          }, 2000)
          GetEventos()
        } else {
          swal("Error", d.data, "error")
        }
      })
    }
    vm.Imprimir = () => {
      vm.Impresion = true
      printDiv()
    }
    vm.ShowEvento = function (EventoId) {
      vm.EventosAnteriores = []
      vm.UEvento = []
      TmEventoService.getEventoByEventoId(EventoId).then(function (e) {
        vm.UEvento = e.data
        TmEventoService.getEventosByMaternaIdMenosEste(vm.UEvento.MaternaId, vm.UEvento.EventoId).then((c) => {
          vm.EventosAnteriores = c.data
          $('#UpdateEventoModal').modal('show')
        })
      })
    }
    vm.CrearEventoModal = function () {
      $('#CrearEventoModal').modal('show')
    }
    vm.GuardarEvento = function () {
      if (!vm.CargandoBandera) {
        var obj = {
          Evento: JSON.stringify(vm.Evento)
        }
        if (!vm.Datos.$valid) {
          angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus()
        } else if (vm.Evento.Detalles.length === 0 && vm.Evento.TipoEvento != "Control Prenatal") {
          swal("Error", 'Debe seleccionar una tarifa.', "error")
        } else {
          vm.CargandoBandera = true
          TmEventoService.postTmEvento(obj).then(function (d) {
            if (typeof d.data != "string") {
              swal("Enhorabuena", "Se ha guardado el evento con exito", "success")
              $('#CrearEventoModal').modal('hide')
              GetEventos()
              Reset()
            } else {
              swal("Error", d.data, "error")
            }
            vm.CargandoBandera = false
          }, () => {
            vm.CargandoBandera = false
          })
        }
      }
    }

    vm.GetMaternaByDocumento = function () {
      GetMaternaByDocumento()
    }
    vm.ChangeTransporte = () => {
      vm.Evento.Detalles = []
      if (vm.Evento.Documento != '' && vm.Evento.TipoTransporte == 'Particular') {
        GetTarifaByMaterna()
      }
    }
    vm.GetTarifa = function () {
      var Color = getRandomColor()
      TarifaService.getTarifaByTarifaId(vm.TarifaId).then(function (t) {
        if (typeof t.data != "string" && t.data.length > 0) {
          for (var i in t.data) {
            t.data[i].Color = Color
          }
          vm.Evento.Detalles = vm.Evento.Detalles.concat(t.data)
          vm.TarifaId = ""
          if (vm.Evento.Acompanante) {
            for (var i in vm.Evento.Detalles) {
              vm.Evento.Detalles[i].PrecioMaterna = angular.copy(vm.Evento.Detalles[i].Precio)
              vm.Evento.Detalles[i].PrecioAcompanante = angular.copy(vm.Evento.Detalles[i].Precio)
              if (vm.Evento.Detalles[i].Nombre !== "TAXI" && !vm.Evento.Detalles[i].Multiplicado) {
                vm.Evento.Detalles[i].Precio = vm.Evento.Detalles[i].Precio * 2
                vm.Evento.Detalles[i].Multiplicado = true
              }
            }
          }
        }
      })
    }
    vm.setAcompanante = function () {
      vm.Evento.Acompanante = vm.Evento.Acompanante ? false : true
      if (vm.Evento.Acompanante) {
        for (var i in vm.Evento.Detalles) {
          vm.Evento.Detalles[i].PrecioMaterna = angular.copy(vm.Evento.Detalles[i].Precio)
          vm.Evento.Detalles[i].PrecioAcompanante = angular.copy(vm.Evento.Detalles[i].Precio)
          if (vm.Evento.Detalles[i].Nombre !== "TAXI" && !vm.Evento.Detalles[i].Multiplicado) {
            vm.Evento.Detalles[i].PrecioViejo = angular.copy(vm.Evento.Detalles[i].Precio)
            vm.Evento.Detalles[i].Precio = vm.Evento.Detalles[i].PrecioMaterna + vm.Evento.Detalles[i].PrecioAcompanante
            vm.Evento.Detalles[i].Multiplicado = true
          }
        }
      } else {
        for (var i in vm.Evento.Detalles) {
          if (vm.Evento.Detalles[i].Nombre !== "TAXI" && vm.Evento.Detalles[i].Multiplicado) {
            vm.Evento.Detalles[i].Precio = vm.Evento.Detalles[i].PrecioViejo
            vm.Evento.Detalles[i].Multiplicado = false
          }
        }
      }
    }

    vm.CambiarPrecio = (d) => {
      d.Precio = d.PrecioMaterna + d.PrecioAcompanante
    }
    vm.RemoverDetalle = function (i) {
      vm.Evento.Detalles.splice(i, 1)
    }
    vm.ChangeDepartamento = function () {
      GetMunicipioByDepartamento()
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="consultas">
    function GetEventos() {
      vm.cargado = false
      TmEventoService.getEventos(vm.TipoEvento).then(function (c) {
        console.log(c.data)
        vm.Eventos = c.data
        vm.simpleTableOptions = {
          data: [],
          aoColumns: [{
              mData: 'EventoId'
            },
            {
              mData: 'FechaRegistro'
            },
            {
              mData: 'Nombres'
            },
            {
              mData: 'TipoEvento'
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
        }
        vm.simpleTableOptions.data = SetFormat(c.data)
        vm.cargado = true
      })
    }

    function GetMaternaByDocumento() {
      MaternaService.GetMaternaByDocumento(vm.Evento.Documento).then(function (d) {
        if (typeof d.data != "string" && d.data.length > 0) {
          if (d.data[0].HaveParto > 0) {
            swal("Atención!", 'Esta materna ya tiene un registro de Parto o Parto Externo.', "warning")
          }
          vm.Evento.Nombres = d.data[0].Nombres
          vm.Evento.Telefono = d.data[0].Telefono
          vm.Evento.MunicipioId = d.data[0].MunicipioId
          vm.Evento.DepartamentoId = d.data[0].DepartamentoId
          vm.Evento.MaternaId = d.data[0].MaternaId
          vm.Evento.FechaParto = d.data[0].FechaProbableParto

        } else {
          vm.Evento.Nombres = ""
          vm.Evento.Telefono = ""
          vm.Evento.Comentario = ""
          vm.Evento.Procedimiento = ""
          vm.Evento.MunicipioId = ""
          vm.Evento.DepartamentoId = 11
          vm.Evento.MaternaId = null
        }
      }).then(() => {
        if (vm.Evento.TipoTransporte == 'Particular') {
          GetTarifaByMaterna()
        }
      })
    }

    function GetDepartamentos() {
      DepartamentoService.GetDepartamentos().then(function (d) {
        console.log(d.data)
        vm.Departamentos = d.data
      }).then(function () {
        GetMunicipioByDepartamento()
      })
    }

    function GetMunicipioByDepartamento() {
      MunicipioService.GetMunicipiosByDepartamentoId(vm.Evento.DepartamentoId).then(function (m) {
        console.log(m.data)
        vm.Municipios = m.data
      })
    }

    function GetEmpresa() {
      EmpresaService.getEmpresa().then(function (e) {
        vm.Empresa = e.data
      })
    }

    function GetEncabezado() {
      EncabezadoService.getEncabezado().then(function (e) {
        vm.Encabezado = e.data
      })
    }

    function GetTarifaByMaterna() {
      TarifaService.getTarifaByMaterna(vm.Evento.Documento).then(function (t) {
        vm.Evento.Detalles = t.data
      })
    }

    function GetTarifaByMunicipio() {
      TarifaService.getTarifaByMunicipio(vm.Evento.MunicipioId).then(function (t) {
        vm.Evento.Detalles = t.data
      })
    }

    function getRandomColor() {
      var letters = 'BCDEF'.split('')
      var color = '#'
      for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * letters.length)]
      }
      return color
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    function Reset() {
      vm.UEvento = {
        Nombres: "",
        Documento: "",
        Telefono: "",
        DepartamentoId: 11,
        MunicipioId: "",
        Procedimiento: "",
        Comentario: "",
        FechaParto: "",
        TipoEvento: "Control Prenatal",
        TipoTransporte: "Particular",
        Detalles: [],
        Acompanante: false,
        MaternaId: null,
        ModifiedBy: $rootScope.username.NombreCompleto
      }
      vm.Evento = {
        Nombres: "",
        Documento: "",
        Telefono: "",
        DepartamentoId: 11,
        MunicipioId: "",
        Procedimiento: "",
        Comentario: "",
        TipoEvento: "Control Prenatal",
        TipoTransporte: "Particular",
        FechaParto: "",
        Detalles: [],
        Acompanante: false,
        MaternaId: null,
        CreatedBy: $rootScope.username.NombreCompleto
      }
    }

    function toDate(dateStr) {
      var parts = dateStr.split("-")
      return new Date(parts[0], parts[1] - 1, parts[2])
    }

    function toConsultar(dateStr) {
      var parts = dateStr.split("-")
      return parts[0] + "-" + parts[1] + "-" + parts[2]
    }

    function printDiv() {
      $("#impresion_evento").print({
        globalStyles: true,
        mediaPrint: false,
        stylesheet: null,
        noPrintSelector: ".no-print",
        iframe: true,
        append: null,
        prepend: null,
        manuallyCopyFormValues: true,
        deferred: $.Deferred(),
        timeout: 1500,
        title: null,
        doctype: '<!doctype html>'
      })
      setTimeout(function () {
        vm.Impresion = false
        vm.$apply()
      }, 1000)
    }

    function SetFormat(lst) {

      for (var i in lst) {
        lst[i].Opciones = '<a class="btn  btn-primary btn-xs icon-only white" onclick=\"angular.element(this).scope().ShowEvento(' + lst[i].EventoId + ')\"/><i class="fa fa-eye"></i></a>'
        //                if(lst[i].Estado == 'Activo' && fechaHoy == lst[i].FechaRegistro.split(" ")[0]){
        //                    lst[i].Opciones += '<a class="btn  btn-danger btn-xs icon-only white" onclick=\"angular.element(this).scope().EliminarEvento(' + lst[i].EventoId + ')\"/><i class="fa fa-trash"></i></a>'
        //                }
      }
      return lst
    }
    //</editor-fold>
    function _init() {
      vm.TipoEvento = "Solicitado"
      GetEmpresa()
      GetEncabezado()
      GetEventos()
      GetDepartamentos()
      //            GetTarifas()
    }
    _init()
  }
])