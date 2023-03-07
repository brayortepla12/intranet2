app.controller('VerProtocoloProcesoCtrl', ["$scope", "$stateParams", 'ProcesosService', 'TokenService', '$state', '$builder', "$validator",
  function ($scope, $stateParams, ProcesosService, TokenService, $state, $builder, $validator) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    //vm.Orden = 0
    let vm = $scope
    vm.Devolver = false
    vm.Protocolos = []
    vm.Seguimientos = []
    $builder.forms['JSON'] = null
    vm.CargarAnexo = false
    vm.VB = {
      FlujoTrabajoId: "",
      ProcesoId: "",
      VerificadorId: "",
      VB: "",
      Observacion: "",
      EstadoProceso: "Activo",
      DatosAnexos: "",
      CreatedBy: ""
    }
    vm.UsuarioId = ""
    vm.Proceso = {
      ProtocoloId: "",
      DatosFormulario: "",
      Nombre: "",
      SolicitanteId: "",
      CreatedBy: ""
    }
    vm.UProceso = {
      ProtocoloId: "",
      DatosFormulario: "",
      Nombre: "",
      SolicitanteId: "",
      ModifiedBy: ""
    }
    vm.input = []
    vm.inputEdit = []
    vm.Isloading = false
    var conn = new WebSocket('ws://190.131.221.26:8090/protocolo_proceso')
    conn.onopen = function (e) {
      console.log("Connection protocolo proceso!")
      conn.send(JSON.stringify({
        event: 'connect',
        is_admin: true,
        UsuarioId: -9,
        msg: ""
      }))
    }
    conn.onmessage = function (e) {
      var event = JSON.parse(e.data)
      vm.Clientes = event.clients
      if (event.event !== 'connect' && event.event !== 'connected') {
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

      }
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Botones">
    vm.Imprimir = function () {
      printDiv()
    }
    vm.PrepararDevolver = function (o) {
      console.log(o)
      vm.VB.DevolverVerificadorId = o.VerificadorId
      vm.Devolver = true
    }
    vm.changeView = function (o) {
      vm.Orden = o
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

    vm.Aprobar = function (VerificadorId, FlujoTrabajoId, VB, EstadoProceso, btn_name) {
      if (vm.Isloading) {
        return false
      }
      vm.VB.FlujoTrabajoId = FlujoTrabajoId
      vm.VB.ProcesoId = vm.UProceso.ProcesoId
      vm.VB.EstadoProceso = EstadoProceso
      vm.VB.VB = VB
      vm.VB.VerificadorId = VerificadorId
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
              conn.send(JSON.stringify({
                event: "hola",
                is_admin: false,
                UsuarioId: vm.UProceso.SolicitanteId,
                Envia: vm.VB.CreatedBy,
                msg: "Fue " + btn_name
              }))
              swal("Enhorabuena!", "Se han guardado los datos satisfactoriamente", "success")
              $('#ProcesoModal').modal('hide')
              _init()
            }
          }).catch((err) => { vm.Isloading = false })
        }
      }).error(function () {
        swal("Error!", 'Faltan por llenar campos obligatorios. ', 'error')
      })
    }

    vm.Retornar = function (VerificadorId, FlujoTrabajoId, VB, EstadoProceso, btn_name) {
      if (vm.Isloading) {
        return false
      }
      vm.VB.FlujoTrabajoId = FlujoTrabajoId
      vm.VB.ProcesoId = vm.UProceso.ProcesoId
      vm.VB.EstadoProceso = EstadoProceso
      vm.VB.VB = VB
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
          conn.send(JSON.stringify({
            event: "hola",
            is_admin: false,
            UsuarioId: vm.UProceso.SolicitanteId,
            Envia: vm.VB.CreatedBy,
            msg: "Fue " + btn_name
          }))
          $('#ProcesoModal').modal('hide')
          _init()
        }
      }).catch((err) => { vm.Isloading = false })
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    vm.IsInArray = function (lst, UsuarioId) {
      for (var i in lst) {
        if (lst[i].VerificadorId == UsuarioId && lst[i].VB) {
          return true
        }
      }
      return false
    }

    function printDiv() {
      $("#myElementId").print({
        globalStyles: true,
        mediaPrint: false,
        stylesheet: null,
        noPrintSelector: ".no-print",
        iframe: true,
        append: null,
        prepend: null,
        manuallyCopyFormValues: true,
        deferred: $.Deferred(),
        timeout: 750,
        title: null,
        doctype: '<!doctype html>'
      })
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
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Consultas">
    function GetProceso(Id) {
      vm.Seguimientos = []
      ProcesosService.GetProcesosById(Id).then(function (p) {
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
        })
      })
    }

    function isValidToken(Token) {
      var obj = {
        Token: Token
      }
      TokenService.isValidToken(obj).then(function (d) {
        if (typeof d.data == "object") {
          vm.EmailUsuario = d.data.sub
          console.log(d.data)
          GetProceso(d.data.procesoid)
          vm.UsuarioId = d.data.usuarioid
          vm.VB = {
            FlujoTrabajoId: "",
            ProcesoId: "",
            VerificadorId: "",
            UsuarioId: d.data.usuarioid,
            VB: "",
            Observacion: "",
            EstadoProceso: "Activo",
            DatosAnexos: "",
            CreatedBy: d.data.sub
          }
          vm.Proceso = {
            ProtocoloId: "",
            DatosFormulario: "",
            Nombre: "",
            SolicitanteId: d.data.usuarioid,
            CreatedBy: d.data.sub
          }
          vm.UProceso = {
            ProtocoloId: "",
            DatosFormulario: "",
            Nombre: "",
            SolicitanteId: d.data.usuarioid,
            ModifiedBy: d.data.sub
          }
        } else {
          $state.go("login")
        }
      })
    }

    function _init() {
      isValidToken($stateParams.token)
    }


    //</editor-fold>
    //        printDiv()
    _init()
  }
])