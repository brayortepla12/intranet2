app.controller('SolicitudProcesosLstCtrl', ["$scope", "$rootScope", "ProcesosService", "ProtocoloService", "$builder", "$validator",
  function ($scope, $rootScope, ProcesosService, ProtocoloService, $builder, $validator) {
    //<editor-fold defaultstate="collapsed" desc="Variables Iniciales">
    let vm = $scope
    let popup = {}
    vm.Devolver = false
    vm.simpleTableOptions = null
    vm.Estado = "Activo"
    vm.Orden = 0
    vm.Protocolos = []
    vm.Seguimientos = []
    $builder.forms['JSON'] = null
    vm.CargarAnexo = false
    vm.Isloading = false
    vm.VB = {
      FlujoTrabajoId: "",
      ProcesoId: "",
      VerificadorId: "",
      UsuarioId: $rootScope.username.UserId,
      VB: "",
      Observacion: "",
      EstadoProceso: "Activo",
      DatosAnexos: "",
      CreatedBy: $rootScope.username.NombreCompleto
    }
    vm.Proceso = {
      ProtocoloId: "",
      DatosFormulario: "",
      Nombre: "",
      SolicitanteId: $rootScope.username.UserId,
      CreatedBy: $rootScope.username.NombreCompleto
    }
    vm.UProceso = {
      ProtocoloId: "",
      DatosFormulario: "",
      Nombre: "",
      SolicitanteId: $rootScope.username.UserId,
      ModifiedBy: $rootScope.username.NombreCompleto
    }
    vm.input = []
    vm.inputEdit = []
    var conn = new WebSocket('ws://190.131.221.26:8090/protocolo_proceso')
    conn.onopen = function (e) {
      console.log("Connection protocolo proceso!")
      conn.send(JSON.stringify({
        event: 'connect',
        is_admin: true,
        UsuarioId: $rootScope.username.UserId,
        msg: ""
      }))
    }
    conn.onmessage = function (e) {
      var event = JSON.parse(e.data)
      vm.Clientes = event.clients
      if (event.event !== 'connect' && event.event !== 'connected') {
        vm.cargado = false
        vm.simpleTableOptions = null
        _init()
        if (!vm.AbiertoChat) {
          toastr.options.onclick = function () {
            //                        vm.AbiertoChat = true
            //                        SelectItemByName(event.Nombres)
            //                        vm.$apply()
          }
          toastr.success(event.msg, event.Nombres + " dice: ")

        }
        if (vm.selected) {
          vm.VerDetalles(null)
        }
      }
      if (event.event === 'connect') {
        //                if (vm.simpleTableOptions) {
        //                    vm.simpleTableOptions = null
        //                    _init()
        //                }
      }
    }
    //</editor-fold>
    _init()
    //<editor-fold defaultstate="collapsed" desc="Botones">
    vm.PrepararDevolver = function (o) {
      console.log(o)
      vm.VB.DevolverVerificadorId = o.VerificadorId
      vm.Devolver = true
    }
    vm.OpenURL = function (url, type) {
      var blob = b64toBlob(url.split("base64,")[1], type)
      var blobUrl = window.URL.createObjectURL(blob)
      var w1 = window.open("", "_blank")
      w1.location = blobUrl
      swal("Atención!", "Si tienes problemas para visualizar el archivo, por favor deshabilita el Adblock", "warning")
      swal({
        title: "¿Tienes problemas para visualizar este archivo?",
        text: "Nota: Si tienes problemas para visualizar el archivo, por favor deshabilita el Adblock.",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-primary",
        confirmButtonText: "SI!",
        cancelButtonText: "NO!",
        closeOnConfirm: true,
        closeOnCancel: true
      }, function (isConfirm) {
        if (isConfirm) {
          window.location = blobUrl
        }
      })
    }
    vm.EditProceso = function (i) {
      vm.Seguimientos = []
      ProcesosService.GetProcesosById(vm.simpleTableOptions.data[i].ProcesoId).then(function (p) {
        //                console.log(p.data)
        vm.UProceso = angular.copy(p.data)
        vm.Orden = parseInt(vm.UProceso.OrdenEnCurso)
        ProcesosService.GetProcesoData(vm.UProceso.ProcesoId).then(function (d) {
          console.log(d.data)
          vm.UProceso.FlujoTrabajo = d.data
          for (var i in vm.UProceso.FlujoTrabajo) {
            for (var k in vm.UProceso.FlujoTrabajo[i].Seguimiento) {
              vm.Seguimientos.push(vm.UProceso.FlujoTrabajo[i].Seguimiento[k])
            }
          }
          vm.UProceso.DatosFormulario = JSON.parse(vm.UProceso.DatosFormulario)
          $('#ProcesoModal').modal('show')
        })
      })
    }

    vm.changeView = function (o) {
      vm.Orden = o
    }

    vm.Retornar = function (VerificadorId, FlujoTrabajoId, VB, EstadoProceso, btn_name) {
      if (vm.Isloading) {
        return false
      }
      vm.VB.FlujoTrabajoId = FlujoTrabajoId
      vm.VB.ProcesoId = vm.UProceso.ProcesoId
      vm.VB.EstadoProceso = EstadoProceso
      vm.VB.VB = VB
      vm.VB.OrdenEnCurso = vm.Orden
      vm.VB.VerificadorId = VerificadorId
      var obj = {
        Seguimiento_devolver: JSON.stringify([vm.VB])
      }
      vm.Isloading = true
      ProcesosService.postProcesos(obj).then(function (d) {
        vm.Isloading = false
        if (typeof d.data === "string") {
          swal("Error!", d.data, 'error')
        } else {
          swal("Enhorabuena!", "Se han guardado los datos satisfactoriamente", "success")
          vm.Devolver = false
          // conn.send(JSON.stringify({
          //   event: "hola",
          //   is_admin: false,
          //   UsuarioId: vm.UProceso.SolicitanteId,
          //   Envia: $rootScope.username.NombreCompleto,
          //   msg: "Fue " + btn_name
          // }))
          $('#ProcesoModal').modal('hide')
          _init()
        }
      }).catch((err) => { vm.Isloading = false})

    }

    vm.Aprobar = function (VerificadorId, FlujoTrabajoId, VB, EstadoProceso, btn_name) {
      if (vm.Isloading) {
        return false
      }
      vm.VB.FlujoTrabajoId = FlujoTrabajoId
      vm.VB.ProcesoId = vm.UProceso.ProcesoId
      vm.VB.EstadoProceso = EstadoProceso
      vm.VB.VB = VB
      vm.VB.VerificadorId = VerificadorId
      vm.VB.OrdenEnCurso = vm.Orden
      //            vm.VB.DatosAnexos = JSON.stringify(vm.VB.DatosAnexos)
      var count = 0
      for (var i = 0; i < vm.input.length; i++) {
        var x = vm.input[i].label
        if (document.getElementById(vm.input[i].label) != null) {
          var requerido = $(document.getElementById(vm.input[i].label)).attr("validator-required")
          if (requerido === "true") {
            count++
          }
        }
      }
      $validator.validate($scope, 'JSON').success(function () {
        if (count != 0) {
          swal("Error!", 'Faltan por llenar campos obligatorios. ', 'error')
        } else {
          vm.VB.DatosAnexos = angular.toJson(vm.input)
          var obj = {
            Seguimiento: JSON.stringify([vm.VB])
          }
          vm.Isloading = true
          ProcesosService.postProcesos(obj).then(function (d) {
            vm.Isloading = false
            if (typeof d.data === "string") {
              swal("Error!", d.data, 'error')
            } else {
              swal("Enhorabuena!", "Se han guardado los datos satisfactoriamente", "success")
              // conn.send(JSON.stringify({
              //   event: "hola",
              //   is_admin: false,
              //   UsuarioId: vm.UProceso.SolicitanteId,
              //   Envia: $rootScope.username.NombreCompleto,
              //   msg: "Fue " + btn_name
              // }))
              $('#ProcesoModal').modal('hide')
              _init()
            }
          }).catch((err) => { vm.Isloading = false })
        }
      }).error(function () {
        swal("Error!", 'Faltan por llenar campos obligatorios. ', 'error')
      })
    }

    vm.IsInArray = function (lst, UsuarioId) {
      for (var i in lst) {
        if (lst[i].VerificadorId == UsuarioId && lst[i].VB) {
          return true
        }
      }
      return false
    }

    vm.VerFirmas = (i) => {
      const strWindowFeatures = "location=yes,height=800,width=1600,scrollbars=yes,status=yes"
      const url = '/Polivalente/api/Procesos.php?VERFProcesoId=' + vm.simpleTableOptions.data[i].ProcesoId
      window.open(url, 'Ver Firmas Proceso', strWindowFeatures)
    }

    vm.ChangeEstado = function () {
      GetProcesos()
    }

    vm.VerReporte = (i) => {
      ProcesosService.GetPrefijoByReporteId(vm.simpleTableOptions.data[i].ReporteId).then(r => {
        const strWindowFeatures = "location=yes,height=800,width=1600,scrollbars=yes,status=yes"
        const url = '/intranet-2/#/vsolicitud'
        popup = window.open(url, 'Ver Reporte', strWindowFeatures)
        popup.ReporteId = vm.simpleTableOptions.data[i].ReporteId
        popup.PREFIJO = r.data.data.length > 0 ? r.data.data[0].Prefijo : ""
      })
    }
    vm.NuevaNota = (i) => {
      ProcesosService.GetPrefijoByReporteId(vm.simpleTableOptions.data[i].ReporteId).then(r => {
        const url = '/intranet-2/#/create-nota-solicitud'
        const strWindowFeatures = "location=no,height=800,width=1000,scrollbars=yes,status=yes"
        popup = window.open(url, 'Crear Nota', strWindowFeatures)
        popup.PROCESOID = vm.simpleTableOptions.data[i].ProcesoId
        popup.TIPO = 'PC-admin'
        popup.PREFIJO = r.data.data.length > 0 ? r.data.data[0].Prefijo : ""
      })
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Consulta">
    function GetProcesos() {
      vm.cargado = false
      ProcesosService.GetProcesosByVerificador($rootScope.username.UserId, vm.Estado).then(function (d) {
        console.log(d.data)
        vm.simpleTableOptions = {
          data: [],
          aoColumns: [{
              mData: 'ProcesoId'
            },
            {
              mData: 'Sede'
            },
            {
              mData: 'Servicio'
            },
            {
              mData: 'Protocolo'
            },
            {
              mData: 'Nombre'
            },
            {
              mData: 'CreatedAt'
            },
            {
              mData: 'NombreVerificador'
            },
            {
              mData: 'IsYouTurn'
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
        }
        vm.simpleTableOptions.data = SetFormat(d.data)
        vm.cargado = true
      })
    }

    function GetProtocolos() {
      ProtocoloService.GetAllProtocolo($rootScope.username.UserId).then(function (d) {
        console.log(d.data)
        vm.Protocolos = d.data
      })
    }

    function _init() {
      GetProcesos()
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    function SetFormat(lst) {
      for (var i in lst) {
        if (lst[i].IsYouTurn) {
          lst[i].IsYouTurn = "<img src='/intranet-2/public_html/image/flag_green.png' width='40'/>"
        } else {
          lst[i].IsYouTurn = "--"
        }
        lst[i].Opciones = '<a class="btn btn-info btn-xs icon-only white" data-toggle="tooltip" title="Editar Proceso" onclick=\"angular.element(this).scope().EditProceso(' + i + ')\"><i class="fa fa-pencil"></i></a>'
        if (lst[i].Estado === 'Activo') {
          lst[i].Opciones += '<a class="btn btn-success btn-xs icon-only white" data-toggle="tooltip" title="Ver Firmas" onclick=\"angular.element(this).scope().VerFirmas(' + i + ')\"><i class="fa fa-file-text-o"></i></a>'
        }
        if (lst[i].ReporteId) {
          lst[i].Opciones += '<a class="btn btn-warning btn-xs icon-only white" data-toggle="tooltip" title="Ver Reporte" onclick=\"angular.element(this).scope().VerReporte(' + i + ')\"><i class="fa fa-file-text"></i></a>'
        }
        lst[i].Opciones += '<a class="btn btn-secondary btn-xs icon-only white" style="color:white !important" data-toggle="tooltip" title="Notas" onclick=\"angular.element(this).scope().NuevaNota(' + i + ')\"><i class="fa fa-sticky-note"></i></a>'
      }
      return lst
    }

    function b64toBlob(b64Data, contentType, sliceSize) {
      contentType = contentType || ''
      sliceSize = sliceSize || 512

      var byteCharacters = atob(b64Data)
      var byteArrays = []

      for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        var slice = byteCharacters.slice(offset, offset + sliceSize)

        var byteNumbers = new Array(slice.length)
        for (var i = 0; i < slice.length; i++) {
          byteNumbers[i] = slice.charCodeAt(i)
        }

        var byteArray = new Uint8Array(byteNumbers)

        byteArrays.push(byteArray)
      }

      var blob = new Blob(byteArrays, {
        type: contentType
      })
      return blob
    }
    window.notify = function () {
      popup = {}
    }
    //</editor-fold>
  }
])